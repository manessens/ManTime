<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Form\AuthfitForm;
use Cake\I18n\Date;


class UtilsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cookie', ['expires' => '1 hour']);
        $this->Cookie->config('Authfit', 'path', '/');
        $this->Cookie->configKey('Authfit', [
            'expires' => '1 hour',
            'httpOnly' => true
        ]);
    }

    public function index()
    {
        $this->set('controller', false);
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

        if (in_array($action, ['index', 'sendtime', 'authfit', 'setUnactivUser', 'setActivUser']) && $user['role'] >= \Cake\Core\Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
