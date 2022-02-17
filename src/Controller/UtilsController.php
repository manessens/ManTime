<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Form\AuthfitForm;
use Cake\I18n\Date;
use App\Form\ExportForm;
use Cake\I18n\Time;

class UtilsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cookie', ['expires' => '24 hour']);
        $this->Cookie->config('Authfit', 'path', '/');
        $this->Cookie->configKey('Authfit', [
            'expires' => '24 hours',
            'httpOnly' => true
        ]);
    }

    public function index()
    {
        $this->set('controller', false);
    }

    private function convertToIso($string = '')
    {
        return mb_convert_encoding($string, "ISO-8859-1");
    }

    public function sendtime()
    {
        $semaine = null;
        $annee = null;
        if ($this->request->is('post')) {
            $arrayData = $this->request->getData();
            $semaine = $arrayData['week'];
            $annee = $arrayData['year'];
        }
        $current = (int)date('W');
        if ($semaine === null) {
            $semaine = $current;
        }
        if ($annee === null) {
            $annee = date('Y');
        }
        $lundi = new Date('now');
        $lundi->setTime(00, 00, 00);
        $lundi->setISOdate($annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+7 days');

        $this->loadModel('Users');
        $usersV = $this->Users->find('all')
            ->innerJoinWith('Temps', function ($q) use ($lundi, $dimanche) {
                return $q->where(['Temps.date >=' => $lundi, 'Temps.date <' => $dimanche, 'Temps.validat =' => 1, 'deleted ='=>false]);
            })
            ->distinct(['Users.idu'])
            ->toArray();
        $usersN = $this->Users->find('all')
            ->innerJoinWith('Temps', function ($q) use ($lundi, $dimanche) {
                return $q->where(['Temps.date >=' => $lundi, 'Temps.date <' => $dimanche, 'Temps.validat =' => 0, 'deleted ='=>false]);
            })
            ->distinct(['Users.idu'])
            ->toArray();

        $this->set('controller', false);
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('usersV'));
        $this->set(compact('usersN'));
    }

    public function authfit()
    {
        $form = new AuthfitForm();
        if ($this->request->is(['post'])) {
            $arrayData = $this->request->getData();
            $isValid = $form->validate($arrayData);
            if ($isValid){
                $this->Cookie->delete('Authfit');
                $this->Cookie->write('Authfit', $arrayData);

                $result = $this->getFitnetLink("/FitnetManager/rest/employees");
                $vars = json_decode($result, true);
                if (is_array($vars)) {
                    $this->Flash->success(__('Permission de dialogue avec fitnet accordée pour 1h.'));
                }else{
                    $this->Flash->error(__("Les informations de connection n'ont pas permi l'utilisation des API Fitnet."));
                }

            }
        }

        $this->set('form', $form);
        $this->set('controller', false);
    }

    public function exportrow(){
        $idUserAuth = $this->Auth->user('idu');
        $export = new ExportForm();
        $this->loadModel('Client');
        $arrayClient = $this->Client->find('all')->contain(['Agence'])->toArray();
        $clients = array();
        $agenceClient = array();
        foreach ($arrayClient as $client) {
            $clients[$client->idc] = ucfirst($client->nom_client);
            $agenceClient[$client->idc] = ucfirst($client->agence->nom_agence);
        }
        $this->loadModel('Users');
        $arrayUser = $this->Users->find('all')->contain(['Origine'])->toArray();
        $users = array();
        foreach ($arrayUser as $user) {
            $users[$user->idu] = $user->fullname;
            $userOrigine[$user->idu] = $user->origine->nom_origine;
        }
        if ($this->request->is(['post'])) {
            $arrayData = $this->request->getData();
            $isValid = $export->validate($arrayData);
            if ($isValid) {
                $arrayData['date_debut'] = Time::parse($arrayData['date_debut']);
                $arrayData['date_fin'] = Time::parse($arrayData['date_fin']);
                // get time
                $data=[];
                $buffer=[];
                $query = null;
                $queryError = false;

                $date_debut = $arrayData['date_debut'];
                $date_fin = $arrayData['date_fin'];
                $data_client = $arrayData['client'];
                $data_user = $arrayData['user'];

                $this->loadModel('Temps');
                $query = $this->Temps->find('all')
                    ->where(['date >=' => $date_debut, 'date <=' => $date_fin, 'deleted =' => false]);

                if ( $data_client != null && count($data_client) > 0) {
                    $this->loadModel('Projet');
                    $queryIdProjet = $this->Projet->find('list',['fields' =>['idc','idp']]);
                    foreach ($data_client as $client) {
                        $queryIdProjet->orWhere(['idc =' => $client]);
                    };
                    $arrayIdProjet = $queryIdProjet->toArray();
                    if (!empty($arrayIdProjet)) {
                        $query->andWhere(['idp IN' => $arrayIdProjet]);
                    } else {
                        $queryError = true;
                    }
                }
                if (is_array($data_user)) {
                    if (count($data_user) > 0 ){
                        foreach ($data_user as $userId) {
                            $queryUser[] = ['idu =' => $userId];
                        }
                        $query->andWhere(['OR' => $queryUser ]);
                    }
                }elseif($data_user != null){
                    $queryUser[] = ['idu =' => $data_user];
                    $query->andWhere(['OR' => $queryUser ]);
                }
                if ($queryError) {
                    $times = array();
                }else{
                    $times = $query->contain(['Projet'=>['Client'=>'Agence', 'Facturable'], 'Users'=>['Origine'], 'Profil', 'Matrice'])->toArray();
                }
                foreach ($times as $time) {
                    dump($time);
                    $buffer = [
                        'client' => $this->convertToIso($time->projet->client->nom_client),
                        'projet' => $this->convertToIso($time->projet->nom_projet),
                        'matrice' => $this->convertToIso($time->matrice->nom_matrice),
                        'profil' => $this->convertToIso($time->profil->nom_profil),
                        'user' => $this->convertToIso($time->projet->client->agence->nom_agence),
                        'facturable' => $this->convertToIso($time->projet->client->facturable->nom_fact)
                    ];
                    $data[] = $buffer;
                }

                $headerFix = ['Client', 'Projet', 'Matrice', 'Profil', 'Consultant', 'Agence', 'Facturable'];
                $_header = $headerFix;
                $_serialize = 'data';
                $_delimiter = ';';
                $this->set(compact('data', '_serialize', '_delimiter', '_header'));
                $this->viewBuilder()->className('CsvView.Csv');
                return;

            } else {
                $this->Flash->error("Une erreur est survenu. Merci de vérifier la saisie ou de retenter ultérieurement");
            }
        }
        asort($clients);
        asort($users);
        $this->set(compact('export'));
        $this->set(compact('clients'));
        $this->set(compact('users'));
        $this->set('controller', false);
    }

    public function authvsa()
    {
        $form = new AuthfitForm();
        if ($this->request->is(['post'])) {
            $arrayData = $this->request->getData();
            $isValid = $form->validate($arrayData);
            if ($isValid){
                $this->Cookie->delete('Authvsa');
                $this->Cookie->write('Authvsa', $arrayData);
                if ( $this->getVsaLogin() ) {
                    $this->Flash->success(__('Permission de dialogue avec fitnet accordée pour 24h.'));
                }else{
                    $this->Flash->error(__("Les informations de connection n'ont pas permi l'utilisation des API VSA."));
                }

            }
        }

        $this->set('form', $form);
        $this->set('controller', false);
    }

    public function setActivUser(){
        if( $this->request->is('ajax') ) {
            $this->autoRender = false; // Pas de rendu
        }
        $retour = false;
        if ($this->request->is(['POST'])) {
             $arrayData = $this->request->getData();
             $retour = $this->activer($arrayData, true);
        }
        return $this->response->withStringBody($retour);
    }

    public function setUnactivUser(){
        if( $this->request->is('ajax') ) {
            $this->autoRender = false; // Pas de rendu
        }
        $retour = false;
        if ($this->request->is(['POST'])) {
             $arrayData = $this->request->getData();
             $retour = $this->activer($arrayData);
        }
        return $this->response->withStringBody($retour);
    }

    private function activer($arrayData, $validat = false){
        $id_user = $arrayData["user"];
        if (!is_numeric($id_user)) {
            return false;
        }
        $semaine = $arrayData["semaine"];
        $annee = $arrayData["annee"];
        if ($semaine == null || $annee == null) {
            return false;
        }
        $lundi = new Date('now');
        $lundi->setTime(00, 00, 00);
        $lundi->setISOdate($annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+7 days');

        $this->loadModel('Temps');
        $this->Temps->query()
            ->update()->set(['validat' => $validat])
            ->where(['date >=' => $lundi, 'date <' => $dimanche, 'idu =' => $id_user, 'deleted ='=>false])
            ->execute();

        return true;
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        // si réinitialisation de mdp nécessaire on reourne faux
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index', 'sendtime', 'authvsa', 'setUnactivUser', 'setActivUser', 'exportrow']) && $user['role'] >= \Cake\Core\Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
