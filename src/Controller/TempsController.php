<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;
use App\Form\ExportForm;
use App\Form\ImportForm;
use Cake\Filesystem\File;
use Cake\Core\Configure;
use App\Controller\ProjetController;

/**
 * Temps Controller
 *
 * @property \App\Model\Table\TempsTable $Temps
 *
 * @method \App\Model\Entity\Temp[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TempsController extends AppController
{

    var $arrayDays;

    public function initialize()
    {
        parent::initialize();
        $this->arrayDays = ['Lu' => 0, 'Ma' => 1, 'Me' => 2, 'Je' => 3, 'Ve' => 4, 'Sa' => 5, 'Di' => 6];
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index($semaine = null, $annee = null)
    {
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

        $idUserAuth = $this->Auth->user('idu');
        $this->loadModel('Users');
        $user = $this->Users->get($idUserAuth);

        $arrayTemps = $this->Temps->find('all')
            ->where(['Temps.idu =' => $idUserAuth])
            ->andWhere(['date >=' => $lundi])
            ->andWhere(['date <' => $dimanche])
            ->andWhere(['deleted =' => false])
            ->contain(['Projet' => ['Client']])
            ->all();

        $buff = array();
        foreach ($arrayTemps as $temps) {
            $buff[$temps->projet->client->nom_client . '.' . $temps->projet->nom_projet . '.' . $temps->n_ligne][] = $temps;
        }
        $retour = $this->getDaysInWeek($buff, $lundi, $dimanche, $idUserAuth);
        $week = $retour[0];
        $validat = $retour[1];

        $exportableTable = TableRegistry::get('Exportable');
        $isLocked = $exportableTable->find('all')->where(['n_sem =' => $semaine, 'annee =' => $annee])->first();
        if (!is_null($isLocked)) {
            $validat = true;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();
            $arrayIdCurrent = array();
            $entities = array();
            $arrayIdentifierLine = array();
            $verif = false;
            // vérif lock by admin
            if (!is_null($isLocked)) {
                $this->Flash->error(__('La semaine à été vérouillé par un admin, veuillez contacter un responsable pour une modification de saisie'));
                return $this->redirect(['action' => 'index', $semaine, $annee]);
            }
            if (array_key_exists('day', $arrayData)) {
                $verif = true;
                $projetTable = TableRegistry::get('Projet');
                foreach ($arrayData['day'] as $line => $arrayDay) {
                    $dayTime = clone $lundi;
                    // $identifierLine = (string) $arrayData['client'][$line] . $arrayData['projet'][$line] .
                    //         $arrayData['profil'][$line] . $arrayData['activities'][$line] . $arrayData['detail'][$line] ;
                    // if (in_array($identifierLine, $arrayIdentifierLine)) {
                    //     $this->Flash->error(__('Duplication de ligne, veuilez contrôler votre saisie avant de réessayer.'));
                    //     $verif = false;
                    // }
                    if (
                        $arrayData['client'][$line] == 0 || $arrayData['projet'][$line] == 0
                        || $arrayData['profil'][$line] == 0 || $arrayData['activities'][$line] == 0
                    ) { continue; }
                    // $arrayIdentifierLine[] = $identifierLine;
                    foreach ($arrayDay as $dataDay) {
                        $idc = explode('.', $arrayData['client'][$line])[1];
                        $arrayIdp = explode('.', $arrayData['projet'][$line]);
                        $idp = $arrayIdp[2];
                        $arrayIdprof = explode('.', $arrayData['profil'][$line]);
                        $arrayIda = explode('.', $arrayData['activities'][$line]);
                        //generate day
                        $day = null;
                        //check if day already exist or new ?
                        if (empty($dataDay['id'])) {
                            $day = $this->Temps->newEntity();
                            $day->idu = $user->idu;
                        } else {
                            // if exist : get data from dtb to make an update
                            $day = $this->Temps->get($dataDay['id']);
                        }

                        $day->time = $dataDay['time'];
                        $day->modify = true;
                        // add to $week to keep the data in case of error and redirect in the same page
                        $week[$line]['idc'] = $arrayData['client'][$line];
                        $week[$line]['idp'] = $arrayData['projet'][$line];
                        $week[$line]['id_profil'] = $arrayData['profil'][$line];
                        $week[$line]['ida'] = $arrayData['activities'][$line];
                        $week[$line][$this->getDay($day->date, $lundi)] = $day;
                        $week[$line]['detail'] = $arrayData['detail'][$line];

                        //If time is not good => skip (deletion)
                        if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                            $dayTime->modify('+1 days');
                            continue;
                        }
                        // Check if you try to overcharg a day
                        if ($dataDay['time'] > 1 && $verif) {
                            $this->Flash->error(__('La saisie journalière ne peux dépasser une journée pleine sur un même projet avec les mêmes rôles'));
                            $verif = false;
                        }
                        //Check if link are good between Client and Projects
                        if ($idc == $arrayIdp[1] && $idp == $arrayIdprof[0] && $idp == $arrayIda[0]) {
                            //For deletion
                            if ($day->idt) {
                                $arrayIdCurrent[] = $dataDay['id'];
                            }

                            $day->date = clone $dayTime;
                            $day->n_ligne = $line;
                            $day->time = $dataDay['time'];
                            $day->validat = $arrayData['validat'];
                            if ($day->idp != $idp) {
                                $projet  = $projetTable->find('all', ['fields' => ['idm', 'prix']])->where(['idp =' => $idp])->first();
                                $day->idm = $projet->idm;
                                $day->prix = $projet->prix;
                            }
                            $day->idp = $idp;
                            $day->id_profil = $arrayIdprof[1];
                            $day->ida = $arrayIda[1];
                            $day->detail = trim($arrayData['detail'][$line]);
                            $entities[] = $day;

                            $dayTime->modify('+1 days');
                        }
                    }
                }
            }
            if ($verif) {
                if (!$validat) { // Si pas de blocage alors on modifie les temps
                    //Deletion
                    $query = $this->Temps->query()
                        ->update()->set(['deleted' => true])
                        ->where([
                            'idu =' => $user->idu,
                            'date >=' => $lundi,
                            'date <' => $dimanche
                        ]);
                    if (!empty($arrayIdCurrent)) {
                        $query->andWhere(['idt NOT IN' => $arrayIdCurrent]);
                    }
                    $query->execute();
                    //Save
                    if (!empty($entities)) {
                        foreach ($entities as $day) {
                            $verif = $verif && $this->Temps->save($day);
                        }
                    }
                } else {
                    $this->Flash->error(__("La semaine a déjà été soumise, les modifications n'ont pus être sauvegardées."));
                    return $this->redirect(['action' => 'index', $semaine, $annee]);
                }
            }

            if ($verif) {
                $this->Flash->success(__('La semaine à été sauvegardée.'));
                return $this->redirect(['action' => 'index', $semaine, $annee]);
            } else {
                $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
            }
        }

        $week = $this->autoCompleteWeek($week);

        $arrayRetour =  array();
        $arrayEmpty = ['0' => '-'];
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche);
        asort($arrayRetour['projets']);
        asort($arrayRetour['clients']);
        asort($arrayRetour['activities']);
        $fullNameUserAuth = $user->fullname;

        $semaine = strlen($semaine) <= 1 ? '0' . $semaine : $semaine;
        $lastWeek = (int)date('W', strtotime('31-12-'.$annee));

        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('lastWeek'));
        $this->set(compact('annee'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('validat'));
        $this->set('projects', array_merge($arrayEmpty, $arrayRetour['projets']));
        $this->set('clients', array_merge($arrayEmpty, $arrayRetour['clients']));
        $this->set('profiles', array_merge($arrayEmpty, $arrayRetour['profiles']));
        $this->set('activities', array_merge($arrayEmpty, $arrayRetour['activities']));
        $this->set('holidays',   $this->getHolidays($annee));
        $this->set('controller', false);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function indexJp($semaine = null, $annee = null)
    {
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

        $idUserJp = Configure::read('jpid');
        $idUserAuth = $this->Auth->user('idu');
        $this->loadModel('Users');
        $user = $this->Users->get($idUserJp);
        // $usersTable = TableRegistry::get('Users');
        // $user = $usersTable->get($idUserAuth);

        $arrayTemps = $this->Temps->find()
            ->select()
            ->where(['Temps.idu =' => $idUserJp])
            ->andWhere(['date >=' => $lundi])
            ->andWhere(['date <' => $dimanche])
            ->andWhere(['deleted =' => false])
            ->andWhere(['Projet.idu =' => $idUserAuth])
            ->contain(['Projet' => ['Client']])
            ->all();
        $buff = array();
        foreach ($arrayTemps as $temps) {
            $buff[$temps->projet->client->nom_client . '.' . $temps->projet->nom_projet . '.' . $temps->n_ligne][] = $temps;
        }
        $retour = $this->getDaysInWeek($buff, $lundi, $dimanche, $idUserJp);
        $week = $retour[0];
        // $validat = $retour[1];

        $validat = false;
        $exportableTable = TableRegistry::get('Exportable');
        $isLocked = $exportableTable->find('all')->where(['n_sem =' => $semaine, 'annee =' => $annee])->first();
        if (!is_null($isLocked)) {
            $validat = true;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();
            $arrayIdCurrent = array();
            $entities = array();
            $arrayIdentifierLine = array();
            $verif = false;
            if (array_key_exists('day', $arrayData)) {
                $verif = true;
                $projetTable = TableRegistry::get('Projet');
                foreach ($arrayData['day'] as $line => $arrayDay) {
                    $dayTime = clone $lundi;
                    if (
                        $arrayData['client'][$line] == 0 || $arrayData['projet'][$line] == 0 || $arrayData['projet'][$line] == 0
                        || $arrayData['profil'][$line] == 0 || $arrayData['profil'][$line] == 0 || $arrayData['activities'][$line] == 0
                    ) { continue; }
                    // $arrayIdentifierLine[] = $identifierLine;
                    foreach ($arrayDay as $dataDay) {
                        $idc = explode('.', $arrayData['client'][$line])[1];
                        $arrayIdp = explode('.', $arrayData['projet'][$line]);
                        $idp = $arrayIdp[2];
                        $arrayIdprof = explode('.', $arrayData['profil'][$line]);
                        $arrayIda = explode('.', $arrayData['activities'][$line]);
                        //generate day
                        $day = null;
                        if (empty($dataDay['id'])) {
                            $day = $this->Temps->newEntity();
                            $day->idu = $idUserJp;
                        } else {
                            $day = $this->Temps->get($dataDay['id']);
                        }
                        $day->time = $dataDay['time'];
                        $day->modify = true;
                        // add to $week to keep the data in case of error and redirect in the same page
                        $week[$line]['idc'] = $arrayData['client'][$line];
                        $week[$line]['idp'] = $arrayData['projet'][$line];
                        $week[$line]['id_profil'] = $arrayData['profil'][$line];
                        $week[$line]['ida'] = $arrayData['activities'][$line];
                        $week[$line][$this->getDay($day->date, $lundi)] = $day;
                        $week[$line]['detail'] = $arrayData['detail'][$line];

                        if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                            $dayTime->modify('+1 days');
                            continue;
                        }
                        if ($idc == $arrayIdp[1] && $idp == $arrayIdprof[0] && $idp == $arrayIda[0]) {
                            //For deletion
                            if ($day->idt) {
                                $arrayIdCurrent[] = $dataDay['id'];
                            }

                            $day->date = clone $dayTime;
                            $day->n_ligne = $line;
                            $day->time = $dataDay['time'];
                            $day->validat = 1;
                            if ($day->idp != $idp) {
                                $projet  = $projetTable->find('all', ['fields' => ['idm', 'prix']])->where(['idp =' => $idp])->first();
                                $day->idm = $projet->idm;
                                $day->prix = $projet->prix;
                            }
                            $day->idp = $idp;
                            $day->id_profil = $arrayIdprof[1];
                            $day->ida = $arrayIda[1];
                            $day->detail = trim($arrayData['detail'][$line]);
                            $entities[] = $day;

                            $dayTime->modify('+1 days');
                        }
                    }
                }
            }
            if ($verif) {
                // if (!$validat) { // Si pas de blocage alors on modifie les temps
                //Deletion
                $query = $this->Temps->query()
                    ->update()->set(['deleted' => true])
                    ->innerJoinWith('Projet.Participant')
                    ->where([
                        'idu =' => $idUserJp,
                        'date >=' => $lundi,
                        'date <' => $dimanche
                    ])
                    ->andWhere(['Participant.idu =' => $idUserJp]);
                if (!empty($arrayIdCurrent)) {
                    $query->andWhere(['idt NOT IN' => $arrayIdCurrent]);
                }
                $query->execute();
                //Save
                if (!empty($entities)) {
                    foreach ($entities as $day) {
                        $verif = $verif && $this->Temps->save($day);
                    }
                }
            }
            if ($verif) {
                $this->Flash->success(__('La semaine à été sauvegardée.'));
                return $this->redirect(['action' => 'index_jp', $semaine, $annee]);
            } else {
                $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
            }
        }

        $week = $this->autoCompleteWeek($week);

        $arrayRetour =  array();
        $arrayEmpty = ['0' => '-'];
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche, $idUserAuth);
        asort($arrayRetour['projets']);
        asort($arrayRetour['clients']);
        asort($arrayRetour['activities']);
        $fullNameUserAuth = $user->fullname;

        $semaine = strlen($semaine) <= 1 ? '0' . $semaine : $semaine;
        $lastWeek = (int)date('W', strtotime('31-12-'.$annee));

        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('lastWeek'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('validat'));
        $this->set('projects', array_merge($arrayEmpty, $arrayRetour['projets']));
        $this->set('clients', array_merge($arrayEmpty, $arrayRetour['clients']));
        $this->set('profiles', array_merge($arrayEmpty, $arrayRetour['profiles']));
        $this->set('activities', array_merge($arrayEmpty, $arrayRetour['activities']));
        $this->set('holidays',   $this->getHolidays($annee));
        $this->set('controller', false);
    }

    private function build_array($users = [], $clients = [], $projects = [], $profiles = [], $activities = [] ){
        $optionUsers = [];
        $valueUsers = [];
        $optionClients = [];
        $valueClients = [];
        $optionProjects = [];
        $valueProjects = [];
        $optionProfils = [];
        $valueProfils = [];
        $optionActivits = [];
        $valueActivits = [];
        foreach ($users as $key => $value){
                $optionUsers[] = $key;
                $valueUsers[$key] = $value;
        }
        foreach ($clients as $key => $value){
            $arrayTemp = explode('.', $key);
            if (array_key_exists ($arrayTemp[0], $optionClients) ) {
                $optionClients[$arrayTemp[0]][] = $key;
            } else {
                $optionClients[$arrayTemp[0]] = [];
                $optionClients[$arrayTemp[0]][] = $key;
            }
            $valueClients[$key] = $value;
        }
        foreach ($projects as $key => $value){
            $arrayTemp = explode('.', $key);
            if (count ($arrayTemp) >= 2) {
                if (array_key_exists($arrayTemp[0] . '.' . $arrayTemp[1], $optionProjects) ){
                    $optionProjects[$arrayTemp[0] . '.' . $arrayTemp[1]][] = $key;
                } else {
                    $optionProjects[$arrayTemp[0] . '.' . $arrayTemp[1]] = [];
                    $optionProjects[$arrayTemp[0] . '.' . $arrayTemp[1]][] = $key;
                }
            }else{
                if (array_key_exists($arrayTemp[0], $optionProjects) ){
                    $optionProjects[$arrayTemp[0]][] = $key;
                } else {
                    $optionProjects[$arrayTemp[0]] = [];
                    $optionProjects[$arrayTemp[0]][] = $key;
                }
            }
            $valueProjects[$key] = $value;
        }
        foreach ($profiles as $key => $value){
            $arrayTemp = explode('.', $key);
            if (array_key_exists($arrayTemp[0], $optionProfils) ) {
                $optionProfils[$arrayTemp[0]][] = $key;
            } else {
                $optionProfils[$arrayTemp[0]] = [];
                $optionProfils[$arrayTemp[0]][] = $key;
            }
            $valueProfils[$key] = $value;
        }
        foreach ($activities as $key => $value){
            $arrayTemp = explode('.', $key);
            if (array_key_exists($arrayTemp[0], $optionActivits) ) {
                $optionActivits[$arrayTemp[0]][] = $key;
            } else {
                $optionActivits[$arrayTemp[0]] = [];
                $optionActivits[$arrayTemp[0]][] = $key;
            }
            $valueActivits[$key] = $value;
        }
        $retun =[
            '$optionUsers' => $optionUsers,
            '$valueUsers' => $valueUsers,
            '$optionClients' => $optionClients,
            '$valueClients' => $valueClients,
            '$optionProjects' => $optionProjects,
            '$valueProjects' => $valueProjects,
            '$optionProfils' => $optionProfils,
            '$valueProfils' => $valueProfils,
            '$optionActivits' => $optionActivits,
            '$valueActivits' => $valueActivits
        ];

        return $retun;
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function indexCp($semaine = null, $annee = null)
    {

        $current = (int)date('W');

        if ($semaine === null) {
            $semaine = $current;
        }
        if ($annee === null) {
            $annee = date('Y');
        }

        $lundi = new Date('now');
        $lundi->setTime(00, 00, 00);
        $lundi->setISOdate((int)$annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+7 days');

        $this->loadModel('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $this->Users->get($idUserAuth);

        $users = $this->Users->find('all')->toArray();
        $arrayRetour = array('users' => ['0' => '-'], 'projets' => ['0' => '-'], 'clients' => ['0' => '-'], 'profiles' => ['0' => '-'], 'activities' => ['0' => '-']);
        foreach ($users as $key => $userAll) {
            $arrayRetour['users'][$userAll->idu] = $userAll->fullname;
            $arrayTemps = array();
            $arrayTemps = $this->Temps->find('all')
                ->where(['Temps.idu =' => $userAll->idu])
                ->andWhere(['validat =' => 1])
                ->andWhere(['deleted =' => false])
                ->andWhere(['date >=' => $lundi])
                ->andWhere(['date <' => $dimanche])
                ->andWhere(['Projet.idu =' => $idUserAuth])
                ->contain(['Projet' => ['Client']])->all();
            $buff = array();
            foreach ($arrayTemps as $temps) {
                $buff[$temps->projet->client->nom_client . '.' . $temps->projet->nom_projet . '.' . $temps->n_ligne][] = $temps;
            }
            $retour = $this->getDaysInWeek($buff, $lundi, $dimanche, $userAll->idu);
            $week[$userAll->idu] = $retour[0];
        }

        $validat = false;
        $this->loadModel('Exportable');
        $isLocked = $this->Exportable->find('all')->where(['n_sem =' => $semaine, 'annee =' => $annee])->first();
        if (!is_null($isLocked)) {
            $validat = true;
        }

        //test si tratement de donnée
        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();

            $arrayIdDelete = array();
            $entities = array();
            $arrayIdentifierLine = array();

            $verif = true;
            if ( $validat == true) {
                $verif = false;
            }

            if (array_key_exists('day', $arrayData) && $validat == false) {
                $this->loadModel('Projet');
                foreach ($arrayData['day'] as $idUser => $arrayLine) {
                    // control user is connected
                    if ($idUser === 0) { continue; }
                    // for each row in the array
                    foreach ($arrayLine as $line => $arrayDay) {
                        //Control on value : not null
                        if ( $arrayData['users'][$idUser][$line] == 0
                          || $arrayData['client'][$idUser][$line] == 0
                          || $arrayData['projet'][$idUser][$line] == 0
                          || $arrayData['profil'][$idUser][$line] == 0
                          || $arrayData['activities'][$idUser][$line] == 0
                        ) { continue; }
                        // for each Day of the week in a row
                        foreach ($arrayDay as $daySemaine => $dataDay) {
                            $idu = $arrayData['users'][$idUser][$line];
                            $arrayIdc = explode('.', $arrayData['client'][$idUser][$line]);
                            $arrayIdp = explode('.', $arrayData['projet'][$idUser][$line]);
                            $arrayIdprof = explode('.', $arrayData['profil'][$idUser][$line]);
                            $arrayIda = explode('.', $arrayData['activities'][$idUser][$line]);
                            //Generate Day
                            $day = null;
                            // Si ID null : création
                            if (empty($dataDay['id'])) {
                                // Si temps invalide : pas de création
                                if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                                    continue;
                                }else{
                                    $day = $this->Temps->newEntity();
                                    $day->validat = 1;
                                }
                            } else {
                                // Si modification avec temps invalide : suppression
                                if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                                    $arrayIdDelete[] = $dataDay['id'];
                                    continue;
                                }else{
                                    $day = $this->Temps->get($dataDay['id'], ['contain' => []]);
                                }
                            }

                            $day->time = $dataDay['time'];
                            // add to $week to keep the data in case of error and redirect in the same page
                            $week[$idUser][$line]['idc'] = $arrayData['client'][$idUser][$line];
                            $week[$idUser][$line]['idp'] = $arrayData['projet'][$idUser][$line];
                            $week[$idUser][$line]['id_profil'] = $arrayData['profil'][$idUser][$line];
                            $week[$idUser][$line]['ida'] = $arrayData['activities'][$idUser][$line];
                            $week[$idUser][$line][$this->getDay($day->date, $lundi)] = $day;
                            $week[$idUser][$line]['nline'] = $line;
                            $week[$idUser][$line]['detail'] = $arrayData['detail'][$idUser][$line];

                            if (
                                $idu == $arrayIdc[0] && $idu == $arrayIdp[0]
                                && $arrayIdc[1] == $arrayIdp[1] && $arrayIdp[2] == $arrayIdprof[0] && $arrayIdp[2] == $arrayIda[0]
                            ) {
                                $day->idu = $idUser;
                                $day->deleted = false;

                                // détermination de la date en fonction du jour de la semaine
                                $dayTime = clone $lundi;
                                $dayTime->modify('+' . $this->arrayDays[$daySemaine] . ' days');
                                $day->date = clone $dayTime;

                                $day->n_ligne = $line;
                                $day->validat = 1;
                                if ($day->idp != $arrayIdp[2]) {
                                    $projet  = $this->Projet->find('all', ['fields' => ['idm', 'prix']])->where(['idp =' => $arrayIdp[2]])->first();
                                    $day->idm = $projet->idm;
                                    $day->prix = $projet->prix;
                                }
                                $day->idp = $arrayIdp[2];
                                $day->id_profil = $arrayIdprof[1];
                                $day->ida = $arrayIda[1];
                                $day->detail = trim($arrayData['detail'][$idUser][$line]);
                                $entities[] = $day;
                            }
                        }
                    }
                }
            }

            // si pas d'erreur et la requete ne provient pas de la page locked et pas de blocage alors on modifie les temps
            if ($verif && !array_key_exists('check_lock', $arrayData)) {
                if (!empty($arrayIdDelete)) { // Si il y a des temps à supprimer
                    //Deletion
                    $query = $this->Temps->query()
                        ->update()->set(['deleted' => true])
                        ->where([
                            'validat =' => 1,
                            'modify = ' => false,
                            'date >=' => $lundi,
                            'date <' => $dimanche
                        ]);
                    $query->andWhere(['idt IN' => $arrayIdDelete]);
                    $query->execute();
                }
                //Save
                if (!empty($entities)) {
                    foreach ($entities as $day) {
                        try {
                            $this->Temps->saveOrFail($day);
                        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                            $oldDay = $this->Temps->find('all')->where([
                                'idu =' => $day->idu,
                                'idp =' => $day->idp, 'id_profil =' => $day->id_profil,
                                'ida =' => $day->ida, 'date =' => $day->date
                            ])->first();

                            if (!is_null($oldDay)) {
                                $oldDay->time = $day->time;
                                $oldDay->n_ligne = $day->n_ligne;
                                $oldDay->validat = $day->validat;
                                $verif = $verif && $this->Temps->save($oldDay);
                            } else {
                                $verif = false;
                            }
                        }
                    }
                }
            }

            if ( array_key_exists('check_lock', $arrayData) ) {
                if ($verif) {
                    $this->Flash->success(__('La semaine à été sauvegardée.'));
                }else{
                    $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
                }
                return $this->redirect(['action' => 'index-cp', $semaine, $annee]);
            }else {
                // on ne génère pas la page si on viens d'un appel JS
                $this->autoRender = false; // Pas de rendu
                return $this->response->withStringBody($verif);
            }
        }

        $arrayBuff = array();
        foreach ($week as $idu => $weekUser) {
            $week[$idu] = $this->autoCompleteWeek($weekUser, true);

            $arrayBuff = $this->getProjects($idu, $lundi, $dimanche);
            $arrayRetour['projets']   = array_merge($arrayRetour['projets'], $arrayBuff['projets']);
            $arrayRetour['clients']   = array_merge($arrayRetour['clients'], $arrayBuff['clients']);
            $arrayRetour['profiles']  = array_merge($arrayRetour['profiles'], $arrayBuff['profiles']);
            $arrayRetour['activities'] = array_merge($arrayRetour['activities'], $arrayBuff['activities']);
        }
        asort($arrayRetour['users']);
        asort($arrayRetour['projets']);
        asort($arrayRetour['clients']);
        asort($arrayRetour['activities']);
        $fullNameUserAuth = $user->fullname;

        $semaine = strlen($semaine) <= 1 ? '0' . $semaine : $semaine;
        $lastWeek = (int)date('W', strtotime('31-12-'.$annee));

        $arrays = $this->build_array($arrayRetour['users'], $arrayRetour['clients'], $arrayRetour['projets'], $arrayRetour['profiles'], $arrayRetour['activities']);
        // $this->set(compact('temps'));
        $this->set(compact('arrays'));
        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('lastWeek'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('validat'));
        $this->set('users',      $arrayRetour['users']);
        $this->set('projects',   $arrayRetour['projets']);
        $this->set('clients',    $arrayRetour['clients']);
        $this->set('profiles',   $arrayRetour['profiles']);
        $this->set('activities', $arrayRetour['activities']);
        $this->set('holidays',   $this->getHolidays($annee));
        $this->set('controller', false);
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function indexAdmin($semaine = null, $annee = null)
    {

        $current = (int)date('W');

        if ($semaine === null) {
            $semaine = $current;
        }
        if ($annee === null) {
            $annee = date('Y');
        }

        $lundi = new Date('now');
        $lundi->setTime(00, 00, 00);
        $lundi->setISOdate((int)$annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+7 days');

        $this->loadModel('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $this->Users->get($idUserAuth);

        $users = $this->Users->find('all')->toArray();
        $arrayRetour = array('users' => ['0' => '-'], 'projets' => ['0' => '-'], 'clients' => ['0' => '-'], 'profiles' => ['0' => '-'], 'activities' => ['0' => '-']);
        foreach ($users as $key => $userAll) {
            $arrayRetour['users'][$userAll->idu] = $userAll->fullname;
            $arrayTemps = array();
            $arrayTemps = $this->Temps->find('all')
                ->where(['Temps.idu =' => $userAll->idu])
                ->andWhere(['validat =' => 1])
                ->andWhere(['deleted =' => false])
                ->andWhere(['date >=' => $lundi])
                ->andWhere(['date <' => $dimanche])
                ->contain(['Projet' => ['Client']])->all();
            $buff = array();
            foreach ($arrayTemps as $temps) {
                $buff[$temps->projet->client->nom_client . '.' . $temps->projet->nom_projet . '.' . $temps->n_ligne][] = $temps;
            }
            $retour = $this->getDaysInWeek($buff, $lundi, $dimanche, $userAll->idu);
            $week[$userAll->idu] = $retour[0];
        }

        $validat = false;
        $this->loadModel('Exportable');
        $isLocked = $this->Exportable->find('all')->where(['n_sem =' => $semaine, 'annee =' => $annee])->first();
        if (!is_null($isLocked)) {
            $validat = true;
        }

        //test si tratement de donnée
        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();
            if (!array_key_exists('validat', $arrayData)) {
                $this->Flash->error(__('Longueur maximal de requête atteinte ! Veuillez consulter un responsable avant de continuer.'));
                return $this->redirect(['action' => 'index-admin', $semaine, $annee]);
            }
            $arrayIdDelete = array();
            $entities = array();
            $verif = true;
            $arrayIdentifierLine = array();

            if (array_key_exists('day', $arrayData)) {
                $this->loadModel('Projet');
                foreach ($arrayData['day'] as $idUser => $arrayLine) {
                    if ($idUser === 0) {
                        continue;
                    }
                    foreach ($arrayLine as $line => $arrayDay) {
                        // $dayTime = clone $lundi;
                        // $identifierLine = $arrayData['users'][$idUser][$line] . $arrayData['client'][$idUser][$line] .
                        //     $arrayData['projet'][$idUser][$line] . $arrayData['profil'][$idUser][$line] .
                        //     $arrayData['activities'][$idUser][$line] . $arrayData['detail'][$idUser][$line] ;
                        // if (in_array($identifierLine, $arrayIdentifierLine)) {
                        //     $this->Flash->error(__('Duplication de ligne, veuilez contrôler votre saisie avant de réessayer.'));
                        //     $verif = false;
                        // }
                        if (
                            $arrayData['users'][$idUser][$line] == 0
                            || $arrayData['client'][$idUser][$line] == 0
                            || $arrayData['projet'][$idUser][$line] == 0
                            || $arrayData['profil'][$idUser][$line] == 0
                            || $arrayData['activities'][$idUser][$line] == 0
                        ) {
                            continue;
                        }
                        // $arrayIdentifierLine[] = $identifierLine;
                        foreach ($arrayDay as $daySemaine => $dataDay) {
                            $idu = $arrayData['users'][$idUser][$line];
                            $arrayIdc = explode('.', $arrayData['client'][$idUser][$line]);
                            $arrayIdp = explode('.', $arrayData['projet'][$idUser][$line]);
                            $arrayIdprof = explode('.', $arrayData['profil'][$idUser][$line]);
                            $arrayIda = explode('.', $arrayData['activities'][$idUser][$line]);
                            //Generate Day
                            $day = null;

                            // détermination de la date en fonction du jour de la semaine
                            $dayTime = clone $lundi;
                            $dayTime->modify('+' . $this->arrayDays[$daySemaine] . ' days');
                            // Si ID null : création
                            if (empty($dataDay['id'])) {
                                // Si temps invalide : pas de création
                                if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                                    continue;
                                }else{

                                    $queryDay = $this->Temps->find('all')
                                        ->where(['idu =' => $idUser,
                                                 'idp =' => $arrayIdp[2],
                                                 'ida =' => $arrayIda[1],
                                                 'id_profil =' => $arrayIdprof[1],
                                                 'date =' => $dayTime])->first();
                                    // DEBUG:
                                    debug($dayTime);
                                    debug($queryDay);

                                    $day = $this->Temps->newEntity();
                                    $day->validat = 1;
                                }
                            } else {
                                // Si modification avec temps invalide : suppression
                                if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                                    $arrayIdDelete[] = $dataDay['id'];
                                    continue;
                                }else{
                                    $day = $this->Temps->get($dataDay['id'], ['contain' => []]);
                                }
                            }

                            $day->time = $dataDay['time'];
                            // add to $week to keep the data in case of error and redirect in the same page
                            $week[$idUser][$line]['idc'] = $arrayData['client'][$idUser][$line];
                            $week[$idUser][$line]['idp'] = $arrayData['projet'][$idUser][$line];
                            $week[$idUser][$line]['id_profil'] = $arrayData['profil'][$idUser][$line];
                            $week[$idUser][$line]['ida'] = $arrayData['activities'][$idUser][$line];
                            $week[$idUser][$line][$this->getDay($day->date, $lundi)] = $day;
                            $week[$idUser][$line]['nline'] = $line;
                            $week[$idUser][$line]['detail'] = $arrayData['detail'][$idUser][$line];

                            if (
                                $idu == $arrayIdc[0] && $idu == $arrayIdp[0]
                                && $arrayIdc[1] == $arrayIdp[1] && $arrayIdp[2] == $arrayIdprof[0] && $arrayIdp[2] == $arrayIda[0]
                            ) {
                                $day->idu = $idUser;
                                $day->deleted = false;

                                $day->date = clone $dayTime;

                                $day->n_ligne = $line;
                                $day->validat = 1;
                                if ($day->idp != $arrayIdp[2]) {
                                    $projet  = $this->Projet->find('all', ['fields' => ['idm', 'prix']])->where(['idp =' => $arrayIdp[2]])->first();
                                    $day->idm = $projet->idm;
                                    $day->prix = $projet->prix;
                                }
                                $day->idp = $arrayIdp[2];
                                $day->id_profil = $arrayIdprof[1];
                                $day->ida = $arrayIda[1];
                                $day->detail = trim($arrayData['detail'][$idUser][$line]);
                                $entities[] = $day;
                            }
                        }
                    }
                }
            }

            // si pas d'erreur et la requete ne provient pas de la page locked et pas de blocage alors on modifie les temps
            if ($verif && !array_key_exists('check_lock', $arrayData)) {
                if (!empty($arrayIdDelete)) { // Si il y a des temps à supprimer
                    //Deletion
                    $query = $this->Temps->query()
                        ->update()->set(['deleted' => true])
                        ->where([
                            'validat =' => 1,
                            'modify = ' => false,
                            'date >=' => $lundi,
                            'date <' => $dimanche
                        ]);
                    $query->andWhere(['idt IN' => $arrayIdDelete]);
                    $query->execute();
                }
                //Save
                if (!empty($entities)) {
                    foreach ($entities as $day) {
                        try {
                            $this->Temps->saveOrFail($day);
                        } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                            $oldDay = $this->Temps->find('all')->where([
                                'idu =' => $day->idu,
                                'idp =' => $day->idp, 'id_profil =' => $day->id_profil,
                                'ida =' => $day->ida, 'date =' => $day->date
                            ])->first();

                            if (!is_null($oldDay)) {
                                $oldDay->time = $day->time;
                                $oldDay->n_ligne = $day->n_ligne;
                                $oldDay->validat = $day->validat;
                                $verif = $verif && $this->Temps->save($oldDay);
                            } else {
                                $verif = false;
                            }
                        }
                    }
                }
            // si pas d'erreur et la requete ne provient pas de la page locked MAIS qu'il y a blocage alors anormal :
            // } else {
            //     $this->Flash->error(__('Les données ont été verrouillées par un autre utilisateur, aucune modification enregistrée.'));
            //     return $this->redirect(['action' => 'index-admin', $semaine, $annee]);
            }

            // Mise à jour du blocage si on viens de la page locked ou si il n'y a pas de clef de blocage existant
            if (array_key_exists('check_lock', $arrayData) || !$validat) {
                if ($arrayData['validat'] === "0" && $validat) {
                    $this->Exportable->delete($isLocked);
                } elseif (($arrayData['validat'] === "" || $arrayData['validat'] === "1") && !$validat) {
                    $locked = $this->Exportable->newEntity();
                    $locked->n_sem = $semaine;
                    $locked->annee = $annee;
                    $this->Exportable->save($locked);
                }
            }

            if ( array_key_exists('check_lock', $arrayData) ) {
                if ($verif) {
                    $this->Flash->success(__('La semaine à été sauvegardée.'));
                }else{
                    $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
                }
                return $this->redirect(['action' => 'index-admin', $semaine, $annee]);
            }else {
                // on ne génère pas la page si on viens d'un appel JS
                $this->autoRender = false; // Pas de rendu
                return $this->response->withStringBody($verif);
            }
        }

        $arrayBuff = array();
        foreach ($week as $idu => $weekUser) {
            $week[$idu] = $this->autoCompleteWeek($weekUser, true);

            $arrayBuff = $this->getProjects($idu, $lundi, $dimanche);
            $arrayRetour['projets']   = array_merge($arrayRetour['projets'], $arrayBuff['projets']);
            $arrayRetour['clients']   = array_merge($arrayRetour['clients'], $arrayBuff['clients']);
            $arrayRetour['profiles']  = array_merge($arrayRetour['profiles'], $arrayBuff['profiles']);
            $arrayRetour['activities'] = array_merge($arrayRetour['activities'], $arrayBuff['activities']);
        }
        asort($arrayRetour['users']);
        asort($arrayRetour['projets']);
        asort($arrayRetour['clients']);
        asort($arrayRetour['activities']);
        $fullNameUserAuth = $user->fullname;

        $semaine = strlen($semaine) <= 1 ? '0' . $semaine : $semaine;
        $lastWeek = (int)date('W', strtotime('31-12-'.$annee));

        $arrays = $this->build_array($arrayRetour['users'], $arrayRetour['clients'], $arrayRetour['projets'], $arrayRetour['profiles'], $arrayRetour['activities']);
        // $this->set(compact('temps'));
        $this->set(compact('arrays'));
        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('lastWeek'));
        $this->set(compact('annee'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('validat'));
        $this->set('users',      $arrayRetour['users']);
        $this->set('projects',   $arrayRetour['projets']);
        $this->set('clients',    $arrayRetour['clients']);
        $this->set('profiles',   $arrayRetour['profiles']);
        $this->set('activities', $arrayRetour['activities']);
        $this->set('holidays',   $this->getHolidays($annee));
        $this->set('controller', false);
    }

    private function getDaysInWeek($buff, $lundi, $dimanche, $idu)
    {
        $week = array();
        $validat = false;
        ksort($buff);
        foreach ($buff as $key => $arrayDayz) {
            $arrKey = explode('.', $key);
            if (count($arrKey) > 2) {
                $key = $arrKey[2];
            }
            foreach ($arrayDayz as $day) {
                $week[$key]['idc'] = $idu . '.' . $day->projet->idc;
                $week[$key]['idp'] = $idu . '.' . $day->projet->idc . '.' . $day->idp;
                $week[$key]['id_profil'] = $day->idp . '.' . $day->id_profil;
                $week[$key]['ida'] = $day->idp . '.' . $day->ida;
                if (!$validat) {
                    $validat = $day->validat;
                }
                $week[$key][$this->getDay($day->date, $lundi)] = $day;
                $week[$key]['detail'] = $day->detail;
                $week[$key]['nline'] = $day->n_ligne;
            }
        }
        return [$week, $validat];
    }

    private function autoCompleteWeek($week, $admin = false)
    {
        $modelWeek = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
        $entities = array();
        foreach ($week as $key => $arrayDayz) {
            foreach ($modelWeek as $idDay) {
                if (!array_key_exists($idDay, $week[$key])) {
                    $week[$key][$idDay] = $this->Temps->newEntity();
                } elseif ($admin) {
                    $day = $week[$key][$idDay];
                    $day->modify = false;
                    $entities[] = $day;
                }
            }
        }
        if (!empty($entities)) {
            $this->Temps->saveMany($entities);
        }
        return $week;
    }

    private function getDay($date, $lundi)
    {
        $date = new Date($date);
        $mardi = clone $lundi;
        $mardi->modify('+1 days');
        $mercredi = clone $lundi;
        $mercredi->modify('+2 days');
        $jeudi = clone $lundi;
        $jeudi->modify('+3 days');
        $vendredi = clone $lundi;
        $vendredi->modify('+4 days');
        $samedi = clone $lundi;
        $samedi->modify('+5 days');
        $dimanche = clone $lundi;
        $dimanche->modify('+6 days');
        if (
            $date >=  $lundi
            && $date <  $mardi
        ) {
            return 'Lu';
        } elseif (
            $date >=  $mardi
            && $date <  $mercredi
        ) {
            return 'Ma';
        } elseif (
            $date >=  $mercredi
            && $date <  $jeudi
        ) {
            return 'Me';
        } elseif (
            $date >=  $jeudi
            && $date <  $vendredi
        ) {
            return 'Je';
        } elseif (
            $date >=  $vendredi
            && $date <  $samedi
        ) {
            return 'Ve';
        } elseif (
            $date >=  $samedi
            && $date <  $dimanche
        ) {
            return 'Sa';
        } else {
            return 'Di';
        }
    }

    private static function getHolidays($year = null)
    {

        if ($year === null) {
            $year = intval(strftime('%Y'));
        }

        // passage de l'année en Int : Mathis Alleaume -> 27/07/2020
        $year = (int)$year;

        $easterDate = easter_date($year);
        $easterDay = date('j', $easterDate) + 1;
        $easterMonth = date('n', $easterDate);

        $holidays = array(
            // Jours fériés fixes
            date('d-m-Y', mktime(0, 0, 0, 1, 1, $year)), // 1er janvier
            date('d-m-Y', mktime(0, 0, 0, 5, 1, $year)), // Fete du travail
            date('d-m-Y', mktime(0, 0, 0, 5, 8, $year)), // Victoire des allies
            date('d-m-Y', mktime(0, 0, 0, 7, 14, $year)), // Fete nationale
            date('d-m-Y', mktime(0, 0, 0, 8, 15, $year)), // Assomption
            date('d-m-Y', mktime(0, 0, 0, 11, 1, $year)), // Toussaint
            date('d-m-Y', mktime(0, 0, 0, 11, 11, $year)), // Armistice
            date('d-m-Y', mktime(0, 0, 0, 12, 25, $year)), // Noel

            // Jour fériés qui dependent de paques
            date('d-m-Y', mktime(2, 0, 0, $easterMonth, $easterDay, $year)), // Pâques
            // date('d-m-Y',mktime(0, 0, 0, $easterMonth, $easterDay + 1, $year)),// Lundi de paques
            // date('d-m-Y',mktime(0, 0, 0, $easterMonth, $easterDay + 39, $year)),// Ascension
            date('d-m-Y', mktime(2, 0, 0, $easterMonth, $easterDay + 38, $year)), // Ascension
            // mktime(0, 0, 0, $easterMonth, $easterDay + 50, $year), // Pentecote => journée de solidarité (facturé en x1)
        );
        sort($holidays);
        return $holidays;
    }

    private function getProjects($idu, $lundi, $dimanche, $idcp = -1)
    {

        $this->loadModel('Participant');
        $this->loadModel('Activities');
        $arrayProjects = array();
        $arrayRetour = array('projets' => [], 'clients' => [], 'profiles' => [], 'activities' => []);

        $particpations = $this->Participant->find('all')
            ->where(['Participant.idu =' => $idu])
            ->andWhere(['date_debut <' => $dimanche->year . $dimanche->i18nFormat('-MM-dd')])
            ->andWhere(['date_fin >=' => $lundi->year . $lundi->i18nFormat('-MM-dd')]);
        if ($idcp > 0) {
            $particpations->andWhere(['Projet.idu =' => $idcp]);
        }
        $particpations->contain(['Projet' => ['Client', 'Matrice' => ['LignMat' => ['Profil']]]])->all();
        foreach ($particpations as $participant) {
            $projet = $participant->projet;
            $arrayProjects[$idu . '.' . $projet->idc . '.' . $projet->idp] = $projet;
            $arrayRetour['projets'][$idu . '.' . $projet->idc . '.' . $projet->idp] = $projet->nom_projet;
        }
        $arrayClients = array();
        foreach ($arrayProjects as $projet) {
            $arrayClients[$idu . '.' . $projet->idc] = $projet->client;
            $arrayRetour['clients'][$idu . '.' . $projet->idc] = $projet->client->nom_client;

            foreach ($projet->matrice->lign_mat as $ligne) {
                $arrayRetour['profiles'][$projet->idp . '.' . $ligne->id_profil] = $ligne->profil->nom_profil;
            }

            $activities = $this->Activities->findByIdp($projet->idp)->contain(['Activitie'])->all();
            foreach ($activities as $activity) {
                $arrayRetour['activities'][$projet->idp . '.' . $activity->ida] = $activity->activitie->nom_activit;
            }
        }

        return $arrayRetour;
    }

    public function getProjectName($id)
    {
        $this->loadModel('Projet');
        $idp = explode('.', $id)[2];
        $project = $this->Projet->get($idp);
        return $this->response->withStringBody($project->nom_projet);
    }

    public function getClientName($id)
    {
        $this->loadModel('Client');
        $idc = explode('.', $id)[1];
        $client = $this->Client->get($idc);
        return $this->response->withStringBody($client->nom_client);
    }

    public function getProfilName($id)
    {
        $this->loadModel('Profil');
        $idprof = explode('.', $id)[1];
        $profil = $this->Profil->get($idprof);
        return $this->response->withStringBody($profil->nom_profil);
    }

    public function getActivitieName($id)
    {
        $this->loadModel('Activitie');
        $ida = explode('.', $id)[1];
        $act = $this->Activitie->get($ida);
        return $this->response->withStringBody($act->nom_activit);
    }

    private function clearDtb()
    {
        $currentYear = new Date('Now');
        $currentYear->modify('-2 years');
        $query = $this->Temps->find('all')
            ->where(['date <=' => $currentYear->year . '-01-01']);
        $listDeletion = $query->toArray();
        if (!empty($listDeletion)) {
            foreach ($listDeletion as $entity) {
                $this->Temps->delete($entity);
            }
        }
    }

    public function getTimes(\Cake\I18n\Time $date_debut, \Cake\I18n\Time $date_fin, $data_client = [], $data_user = [] ){

        $times = array();
        $data = array();
        $periodes = array();
        $this->loadModel('Exportable');
        $semaineDebut = (int)date('W', strtotime($date_debut->i18nFormat('dd-MM-YYYY')));
        $anneeDebut = (int)date('Y', strtotime($date_debut->i18nFormat('dd-MM-YYYY')));
        $semaineFin = (int)date('W', strtotime($date_fin->i18nFormat('dd-MM-YYYY')));
        $anneeFin =   (int)date('Y', strtotime($date_fin->i18nFormat('dd-MM-YYYY')));
        $arraNSem = array($anneeDebut => array());
        $y = $anneeDebut;
        for ($i = $semaineDebut; ($i <= $semaineFin && $y <= $anneeFin); $i++) {
            $lastWeek = (int)date('W', strtotime('31-12-'.$y));
            if ($i > $lastWeek) {
                $i = 1;
                $y++;
            }
            $arraNSem[$y][] = $i;
        }
        $query = null;
        $query = $this->Exportable->find('all');
        $andWhere = array();
        foreach ($arraNSem as $an => $sem) {
            if (!empty($sem)) {
                $andWhere[] = ['n_sem IN' => $sem, 'annee =' => $an];
            }
        }
        $query->where(['OR' => $andWhere]);
        $periodes = $query->toArray();

        $andWhere = array();
        $times = array();
        $queryError = false;
        if (!empty($periodes)) {
            foreach ($periodes as $periode) {
                $lundi = new Date('now');
                $lundi->setTime(00, 00, 00);
                $lundi->setISOdate($periode->annee, $periode->n_sem);
                $dimanche = clone $lundi;
                $dimanche->modify('+7 days');

                $andWhere[] = [
                    'date >=' => $lundi,
                    'date <' => $dimanche,
                ];
            }
            $query = null;
            $query = $this->Temps->find('all')
                ->where(['date >=' => $date_debut, 'date <=' => $date_fin, 'validat =' => 1, 'modify =' => 0, 'deleted =' => false])
                ->andwhere(['OR' => $andWhere]);


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
                return $times;
            }
            $times = $query->toArray();
        }
        return $times;
    }

    public function export()
    {
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
            $this->clearDtb();
            $arrayData = $this->request->getData();
            $isValid = $export->validate($arrayData);
            if ($isValid) {
                $arrayData['date_debut'] = Time::parse($arrayData['date_debut']);
                $arrayData['date_fin'] = Time::parse($arrayData['date_fin']);
                $times = $this->getTimes($arrayData['date_debut'], $arrayData['date_fin'], $arrayData['client'], $arrayData['user']);
                if (empty($times)) {
                    $this->Flash->error("Aucune saisie valide trouvé pour la période demandé.");
                } else {
                    $arrayMonthKey = [
                        1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril', 5 => 'Mai', 6 => 'Juin',
                        7 => 'Juillet', 8 => 'Août', 9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
                    ];

                    $period = array();
                    $arrayMonth = array();
                    $arrayMonthUO = array();
                    $arrayMonthCA = array();
                    if ($arrayData['fitnet']) {
                        $title = 'export_fitnet';
                        $start = $arrayData['date_debut'];
                        $end = $arrayData['date_fin'];
                        while ($start <= $end) {
                            $period[$start->year . $start->month . $start->day] = '';
                            $arrayMonth[] = 'JH ' . $start->i18nFormat('dd-MM-YYYY');
                            $arrayMonthUO[] = 'UO ' . $start->i18nFormat('dd-MM-YYYY');
                            $arrayMonthCA[] = 'CA ' . $start->i18nFormat('dd-MM-YYYY');
                            $start->modify('+1 days');
                        }
                    } else {
                        $title = 'export';
                        $ddebut = $arrayData['date_debut'];
                        $dfin = $arrayData['date_fin'];
                        for ($i = $ddebut->year; $i <= $dfin->year; $i++) {
                            $controlYear = $i === $dfin->year ? false : true;
                            $yb = $i === $ddebut->year ? $ddebut->month : $yb = 1;
                            for ($y = $yb; $y <= $dfin->month || ($i <= $dfin->year && $y <= 12 && $controlYear); $y++) {
                                $period[$i . $y] = '';
                                $arrayMonth[] = 'JH ' . $this->convertToIso($arrayMonthKey[$y]) . ' ' . $i;
                                $arrayMonthUO[] = 'UO ' . $this->convertToIso($arrayMonthKey[$y]) . ' ' . $i;
                                $arrayMonthCA[] = 'CA ' . $this->convertToIso($arrayMonthKey[$y]) . ' ' . $i;
                            }
                        }
                    }
                    $data = $this->getDataFromTimes($times, $users, $clients, $arrayData['fitnet'], $period, $agenceClient, $userOrigine);
                    $this->response->download($title . '.csv');
                    $arrayMonthBuffer = array_merge($arrayMonth, $arrayMonthUO);
                    if (!$arrayData['fitnet']) {
                        $arrayMonthBuffer = array_merge($arrayMonthBuffer, $arrayMonthCA);
                    }
                    $headerFix = ['Client', 'Projet', 'Type', 'Consultant', 'Profil', $this->convertToIso('Activités'), $this->convertToIso('Détails'), 'Agence', 'Facturable', 'Origine'];
                    $_header = array_merge($headerFix, $arrayMonthBuffer);
                    $_serialize = 'data';
                    $_delimiter = ';';
                    $this->set(compact('data', '_serialize', '_delimiter', '_header'));
                    $this->viewBuilder()->className('CsvView.Csv');
                    return;
                }
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

    private function getIncreaseDay($day, $holidays = array())
    {
        $dateDay = date('w', $day->toUnixString());
        $year = $day->i18nFormat('yyyy');
        if (empty($holidays)) {
            $holidays = $this->getHolidays($year);
        }
        // contrôle jour férié : return 2;
        if (in_array($day->i18nFormat('dd-MM-yyyy'), $holidays)) {
            return 2;
        }
        //dimanche 2, samedi 1.5 default 1
        switch ($dateDay) {
            case '6': //Samedi
                return 1.5;
                break;
            case '0': //Dimanche
                return 2;
                break;
            default: // jour de la semaine
                return 1;
                break;
        }
    }

    private function getDataFromTimes($times = array(), $users = array(), $clients = array(), $isFitnet = false, $period, $agenceClient, $userOrigine)
    {

        $ProjetControl = new ProjetController();
        $this->loadModel('Users');
        $idUserAuth = $this->Auth->user('idu');
        $this->loadModel('Projet');
        $arrayprojects = $this->Projet->find('all', ['fields' => ['idp', 'idc', 'nom_projet', 'type', 'Facturable.nom_fact', 'idf']])->contain(['Facturable'])->toArray();
        $projects = $projectClients = array();
        foreach ($arrayprojects as $proj) {
            $projectsType[$proj->projname] = $ProjetControl->getTypeArray()[$proj->type];
            $projects[$proj->idp] = $proj->projname;
            $projectFact[$proj->idp] = $proj->facturable->nom_fact;
            $projectClients[$proj->idp] = $proj->idc;
        }
        $this->loadModel('Profil');
        $profils = $this->Profil->find('list', ['fields' => ['id_profil', 'nom_profil']])->toArray();
        $this->loadModel('Activitie');
        $activits = $this->Activitie->find('list', ['fields' => ['ida', 'nom_activit']])->toArray();

        // $clientTable = TableRegistry::get('Client');
        $matriceTable = TableRegistry::get('Matrice');
        $arrayMatriceHard = $matriceTable->find('all')->contain(['LignMat'])->toArray();
        $arrayMatrice = array();
        foreach ($arrayMatriceHard as $matrice) {
            foreach ($matrice->lign_mat as $lign_mat) {
                $arrayMatrice[$matrice->idm][$profils[$lign_mat->id_profil]]['h'] = $lign_mat->heur;
                $arrayMatrice[$matrice->idm][$profils[$lign_mat->id_profil]]['j'] = $lign_mat->jour;
            }
        }

        $data = array();
        if (empty($times) || !is_array($times)) {
            return $data;
        }
        $nbDays = 0;
        foreach ($times as $time) {
            $keyClient = $clients[$projectClients[$time->idp]];
            $keyAgence = $agenceClient[$projectClients[$time->idp]];
            $keyProject = $projects[$time->idp];
            $keyFact = $projectFact[$time->idp];
            $keyUser = $users[$time->idu];
            $keyOrigine = $userOrigine[$time->idu];
            $keyProfil = $profils[$time->id_profil];
            $keyActivit = $activits[$time->ida];
            $nLine = $this->convertToIso($time->detail) == "" ? $time->n_ligne : $time->n_ligne . '.' . $this->convertToIso($time->detail);
            if (!array_key_exists($keyClient, $data)) {
                $data[$keyClient] = array();
                $data[$keyClient]['-1'] = $keyAgence;
            }
            if (!array_key_exists($keyProject, $data[$keyClient])) {
                $data[$keyClient][$keyProject] = array();
                $data[$keyClient][$keyProject]['-1'] = $keyFact;
            }
            if (!array_key_exists($keyUser, $data[$keyClient][$keyProject])) {
                $data[$keyClient][$keyProject][$keyUser] = array();
                $data[$keyClient][$keyProject][$keyUser]['-1'] = $keyOrigine;
            }
            if (!array_key_exists($keyProfil, $data[$keyClient][$keyProject][$keyUser])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil] = array();
            }
            if (!array_key_exists($keyActivit, $data[$keyClient][$keyProject][$keyUser][$keyProfil])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit] = array();
            }
            if (!array_key_exists($nLine, $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$nLine] = array();
            }
            $dateTime = $time->date;
            if ($isFitnet) {
                $keyDate = $dateTime->year . '-' . $dateTime->month . '-' . $dateTime->day;
            } else {
                $keyDate = $dateTime->year . '-' . $dateTime->month;
            }
            if (!array_key_exists($keyDate, $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$nLine])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$nLine][$keyDate] = array('JH' => 0, 'UO' => 0, 'CA' => 0);
            }
            $nbDays = intval($time->time);
            $timeUO = round(($time->time - $nbDays) * 8, 2) * $arrayMatrice[$time->idm][$keyProfil]['h'];
            $timeUO +=  $nbDays * $arrayMatrice[$time->idm][$keyProfil]['j'];

            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$nLine][$keyDate]['JH'] += $time->time;
            //majoration si samedi : *1.5 dimanche : *2 jour férié : *2
            $timeUO *= $this->getIncreaseDay($dateTime);
            if ($this->Users->get($idUserAuth)->role >= Configure::read('role.cp')) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$nLine][$keyDate]['UO'] += $timeUO;
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$nLine][$keyDate]['CA'] += $timeUO * $time->prix;
            }

            ksort($data[$keyClient]);
            ksort($data[$keyClient][$keyProject]);
            ksort($data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit]);
        }
        ksort($data);
        $dataLine = array();
        $bufferAgence = '';
        $bufferFact = '';
        $bufferOrigine = '';
        foreach ($data as $client => $arrProj) {
            foreach ($arrProj as $projet => $arrUser) {
                if (!is_array($arrUser)) {
                    $bufferAgence = $this->convertToIso($arrUser);
                    continue;
                }
                foreach ($arrUser as $user => $arrProfil) {
                    if (!is_array($arrProfil)) {
                        $bufferFact = $this->convertToIso($arrProfil);
                        continue;
                    }
                    foreach ($arrProfil as $profil => $arrActiv) {
                        if (!is_array($arrActiv)) {
                            $bufferOrigine = $this->convertToIso($arrActiv);
                            continue;
                        }
                        foreach ($arrActiv as $activit => $arrLine) {
                            foreach ($arrLine as $line => $arrDate) {
                                $KLine = explode('.', $line);
                                $zdetail = "";
                                if (count($KLine) > 1) {
                                    array_shift($KLine);
                                    $zdetail = implode('.', $KLine);
                                    $zdetail = trim($zdetail);
                                }
                                $buffer = [
                                    'client' => $this->convertToIso($client), 'projet' => $this->convertToIso($projet),
                                    'type' => $this->convertToIso($projectsType[$projet]),
                                    'user' => $this->convertToIso($user), 'profil' => $this->convertToIso($profil),
                                    'activit' => $this->convertToIso($activit), 'detail' => $zdetail, 'agence' => $bufferAgence,
                                    'facturable' => $bufferFact, 'origine' => $bufferOrigine
                                ];
                                $timebuffer = array();
                                $timebufferMonth = $period;
                                $UobufferMonth = $period;
                                $CabufferMonth = $period;
                                foreach ($arrDate as $date => $arrTime) {
                                    foreach ($arrTime as $type => $time) {
                                        $yearKey = explode('-', $date)[0];
                                        $monthKey = explode('-', $date)[1];
                                        $keyTime = '';
                                        if ($isFitnet) {
                                            $dayKey = explode('-', $date)[2];
                                            $keyTime = $yearKey . $monthKey . $dayKey;
                                        } else {
                                            $keyTime = $yearKey . $monthKey;
                                        }
                                        switch ($type) {
                                            case 'UO':
                                                $UobufferMonth[$keyTime] = str_replace('.', ',', $time);
                                                break;
                                            case 'CA':
                                                $CabufferMonth[$keyTime] = str_replace('.', ',', $time);
                                                break;
                                            default:
                                                $timebufferMonth[$keyTime] = str_replace('.', ',', $time);
                                                break;
                                        }
                                    }
                                }
                                if ($this->Users->get($idUserAuth)->role >= Configure::read('role.cp')) {
                                    $timebufferMonth = array_merge($timebufferMonth, $UobufferMonth);
                                    if (!$isFitnet) {
                                        $timebufferMonth = array_merge($timebufferMonth, $CabufferMonth);
                                    }
                                }
                                $dataLine[] = array_merge($buffer, $timebufferMonth);
                            }
                        }
                    }
                }
            }
        }
        return $dataLine;
    }
    private function convertToIso($string = '')
    {
        return mb_convert_encoding($string, "ISO-8859-1");
    }
    private function convertToUtf($string = '')
    {
        return iconv("ISO-8859-1", "UTF-8", $string);
    }

    public function import()
    {

        $import = new ImportForm();

        $arrayProjetRefused = array();
        $arrayClientRefused = array();
        $arrayUserRefused = array();

        if ($this->request->is(['post'])) {
            $file = $this->request->data['fileimport'];

            $days = array();

            $absFileName = $file['tmp_name'];
            if (file_exists($absFileName)) {
                $this->loadModel('Users');
                $this->loadModel('Projet');
                $this->loadModel('Client');
                $this->loadModel('Profil');
                $this->loadModel('Activitie');
                $lines = file($absFileName, FILE_SKIP_EMPTY_LINES);

                $header = array();

                $users = $this->Users->find('all')->toArray();
                $projets = $this->Projet->find('all', ['contain' => 'Matrice'])->toArray();
                $clients = $this->Client->find('all')->toArray();
                $profils = $this->Profil->find('all')->toArray();
                $activities = $this->Activitie->find('all')->toArray();

                $arrayProjetRefused = array();
                $arrayClientRefused = array();
                $arrayUserRefused = array();
                $arrayProfilRefused = array();
                $arrayActivitieRefused = array();

                $nline = 0;

                foreach ($lines as $n => $line) {
                    $arrayLine = explode(';', $line);
                    // convert into UTF8 the fields that contain string
                    for ($t = 0; $t < 5; $t++) {
                        $arrayLine[$t] = $this->convertToUtf($arrayLine[$t]);
                    }
                    // if key == 0 this is the header that contain the string date
                    if ($n == 0) {
                        $header = $arrayLine;
                        continue;
                    }

                    //User
                    $fullname = explode(' ', $arrayLine[3]);
                    if (count($fullname) < 2) {
                        $arrayUserRefused[$arrayLine[3]] = $fullname;
                        continue;
                    } elseif (count($fullname) > 2) {
                        $name = $fullname[count($fullname) - 1];
                        unset($fullname[count($fullname) - 1]);
                        $forname = implode(' ', $fullname);
                    } else {
                        $name = $fullname[1];
                        $forname = $fullname[0];
                    }
                    $user = array_filter($users, function ($o) use ($name, $forname) {
                        return $o->nom == $name && $o->prenom == $forname;
                    });
                    if (empty($user)) {
                        $arrayUserRefused[$arrayLine[3]] = $fullname;
                        continue;
                    } else {
                        $user = array_shift($user);
                    }

                    $clientName = $arrayLine[0];
                    $client = array_filter($clients, function ($o) use ($clientName) {
                        return $o->nom_client == $clientName;
                    });
                    if (empty($client)) {
                        $arrayClientRefused[$clientName] = $clientName;
                        continue;
                    } else {
                        $client = array_shift($client);
                    }

                    $idc = $client->idc;
                    $projectName = $arrayLine[1];
                    $projet = array_filter($projets, function ($o) use ($projectName, $idc) {
                        return $o->nom_projet == $projectName && $o->idc == $idc;
                    });
                    if (empty($projet)) {
                        $arrayProjetRefused[$projectName] = ['projet' => $projectName, 'client' => $clientName];
                        continue;
                    } else {
                        $projet = array_shift($projet);
                    }

                    $profilName = $arrayLine[4];
                    $profil = array_filter($profils, function ($o) use ($profilName) {
                        return $o->nom_profil == $profilName;
                    });
                    if (empty($profil)) {
                        $arrayProfilRefused[$profilName] = $profilName;
                        continue;
                    } else {
                        $profil = array_shift($profil);
                    }

                    $activitName = $arrayLine[2];
                    $activit = array_filter($activities, function ($o) use ($activitName) {
                        return $o->nom_activit == $activitName;
                    });
                    if (empty($activit)) {
                        $arrayActivitieRefused[$activitName] = $activitName;
                        continue;
                    } else {
                        $activit = array_shift($activit);
                    }

                    // check each date if ther is a time to save
                    for ($i = 5; $i < count($arrayLine); $i++) {
                        // check if value exist
                        if (empty($arrayLine[$i])) {
                            continue;
                        }
                        $day = null;

                        $day = $this->Temps->newEntity();
                        $dateArray = explode('/', $header[$i]);
                        $day->date = new Date($dateArray[0] . '-' . $dateArray[1] . '-' . $dateArray[2]);
                        $day->time = str_replace(',', '.', $arrayLine[$i]);

                        //User
                        $day->idu = $user->idu;
                        $day->idp = $projet->idp;
                        $day->idc = $idc;
                        $day->validat = 1;
                        $day->prix = $projet->prix;
                        $day->idm = $projet->matrice->idm;
                        $day->modify = 0;
                        $day->n_ligne = $nline;
                        $day->id_profil = $profil->id_profil;
                        $day->ida = $activit->ida;

                        // add in array to save all days in a row
                        $days[] = $day;
                    }
                    $nline++;
                }
            }
            if (empty($arrayUserRefused) && empty($arrayClientRefused) && empty($arrayProjetRefused) && empty($arrayProfilRefused) && empty($arrayActivitieRefused)) {
                $result = $this->Temps->saveMany($days);
                if (!$result) {
                    $this->Flash->error(__('Une erreur est survenue à la sauvegarde, contactez un administrateur avant tout autre manipulation.'));
                } else {
                    $this->Flash->success(__('Import terminé avec succés.'));
                }
            } else {
                $this->Flash->error(__('Une erreur a été détectée dans les données.'));
            }
        }

        $this->set(compact('import'));
        $this->set(compact('arrayProjetRefused'));
        $this->set(compact('arrayClientRefused'));
        $this->set(compact('arrayUserRefused'));
        $this->set(compact('arrayProfilRefused'));
        $this->set(compact('arrayActivitieRefused'));
        $this->set('controller', false);
    }


    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if ($user['prem_connect'] === 1) {
            return false;
        }

        // if (in_array($action, ['export']) && $user['role'] >= Configure::read('role.cp') ) {
        if (in_array($action, ['export'])) {
            return true;
        }

        if (in_array($action, ['indexJp', 'indexCp']) && $user['role'] >= Configure::read('role.cp')) {
            return true;
        }

        if (in_array($action, ['indexAdmin', 'import']) && $user['role'] >= Configure::read('role.admin')) {
            return true;
        }

        if (in_array($action, ['index', 'getProjectName', 'getClientName', 'getProfilName', 'getActivitieName'])) {
            return true;
        }

        return false;
    }
}
