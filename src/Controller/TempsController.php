<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\FrozenTime;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;
use App\Form\ExportForm;

/**
 * Temps Controller
 *
 * @property \App\Model\Table\TempsTable $Temps
 *
 * @method \App\Model\Entity\Temp[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TempsController extends AppController
{

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

        $usersTable = TableRegistry::get('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $usersTable->get($idUserAuth);

        $arrayTemps = $this->Temps->find('all')
                ->where(['idu =' => $idUserAuth])
                ->andWhere(['date >=' => $lundi])
                ->andWhere(['date <' => $dimanche])
                ->contain(['Projet' => ['Client']])
                ->all();

        $buff = array();
        foreach ($arrayTemps as $temps) {
            $buff[$temps->n_ligne][] = $temps;
        }
        $retour = $this->getDaysInWeek($buff, $lundi, $dimanche, $idUserAuth);
        $week = $retour[0];
        $validat = $retour[1];

        $exportableTable = TableRegistry::get('Exportable');
        $isLocked = $exportableTable->find('all')->where(['n_sem =' => $semaine, 'annee =' => $annee ])->first();
        if (!is_null($isLocked)) {
            $validat = true;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();
            $arrayIdCurrent = array();
            $entities = array();
            $verif = true;
            $arrayIdentifierLine = array();
            if (array_key_exists('day', $arrayData)) {
                foreach ($arrayData['day'] as $line => $arrayDay) {
                    $dayTime = clone $lundi;
                    $identifierLine = (string) $arrayData['client'][$line] . $arrayData['projet'][$line] . $arrayData['profil'][$line] . $arrayData['activities'][$line] ;
                    if (in_array($identifierLine, $arrayIdentifierLine)) {
                        $this->Flash->error(__('Duplication de ligne, veuilez contrôler votre saisie avant de réessayer.'));
                        $verif = false;
                    }
                    $arrayIdentifierLine[] = $identifierLine;
                    foreach ($arrayDay as $dataDay) {
                        $idc =explode('.',$arrayData['client'][$line])[1];
                        $arrayIdp = explode('.',$arrayData['projet'][$line]);
                        $arrayIdprof = explode( '.', $arrayData['profil'][$line]);
                        $arrayIda = explode('.', $arrayData['activities'][$line]);
                        if (empty($dataDay['time'])) {
                            $dayTime->modify('+1 days');
                            continue;
                        }
                        if ($dataDay['time'] > 1 && $verif) {
                            $this->Flash->error(__('La saisie journalière ne peux dépasser une journée sur un même projet'));
                            $verif = false;
                        }
                        if ($idc==$arrayIdp[1] && $idc==$arrayIdprof[0] && $arrayIdp[2]==$arrayIda[0]) {
                            $day = null;
                            if (empty($dataDay['id'])) {
                                $day = $this->Temps->newEntity();
                                $day->idu = $user->idu;
                            }else{
                                $day = $this->Temps->get($dataDay['id'], [ 'contain' => [] ]);
                                $arrayIdCurrent[] = $dataDay['id'];
                            }
                            $day->date = clone $dayTime ;
                            $day->n_ligne = $line;
                            $day->time = $dataDay['time'];
                            $day->validat = $arrayData['validat'];
                            $day->idp = $arrayIdp[2];
                            $day->id_profil = $arrayIdprof[1];
                            $day->ida = $arrayIda[1];
                            $entities[] = $day;
                            // add to $week to keep the data in case of error and redirect in the same page
                            $week[$line]['idc'] = $idc;
                            $week[$line]['idp'] = $arrayData['projet'][$line];
                            $week[$line]['id_profil'] = $arrayData['profil'][$line];
                            $week[$line]['ida'] = $arrayData['activities'][$line];
                            $week[$line][$this->getDay($day->date, $lundi)] = $day;

                            $dayTime->modify('+1 days');
                        }
                    }
                }
            }
            if ($verif) {
                //Deletion
                if (!empty($arrayIdCurrent)) {
                    $query = $this->Temps->find('all')
                        ->where(['idt  NOT IN' => $arrayIdCurrent, 'idu =' => $user->idu,
                         'date >=' => $lundi,
                         'date <' => $dimanche]);
                }else{
                    $query = $this->Temps->find('all')
                        ->where(['idu =' => $user->idu,
                         'date >=' => $lundi,
                         'date <' => $dimanche]);
                }
                $listDeletion = $query->toArray();
                if (!empty($listDeletion)) {
                    foreach ($listDeletion as  $entity) {
                        $verif = $verif && $this->Temps->delete($entity);
                    }
                }
                //Save
                if (!empty($entities)) {
                    foreach ($entities as $day) {
                        $verif = $verif && $this->Temps->save($day);
                    }
                }
            }
            if ($verif) {
                $this->Flash->success(__('La semaine à été sauvegardé.'));

                return $this->redirect(['controller'=>'Board', 'action' => 'index']);
            }else{
                $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
            }
        }

        $week = $this->autoCompleteWeek($week);

        $arrayRetour =  array();
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche);
        $fullNameUserAuth = $user->fullname;

        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('validat'));
        $this->set('projects', $arrayRetour['projets']);
        $this->set('clients', $arrayRetour['clients']);
        $this->set('profiles', $arrayRetour['profiles']);
        $this->set('activities', $arrayRetour['activities']);
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
        $lundi->setISOdate($annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+7 days');

        $usersTable = TableRegistry::get('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $usersTable->get($idUserAuth);

        $users = $usersTable->find('all')->toArray();
        $arrayRetour = array('users' => array(), 'projets' => array(), 'clients' => array(), 'profiles' => array(), 'activities' => array());
        foreach ($users as $key => $userAll) {
            $arrayRetour['users'][$userAll->idu] = $userAll->fullname;
            $arrayTemps = array();
            $arrayTemps = $this->Temps->find('all')
                    ->where(['idu =' => $userAll->idu])
                    ->andWhere(['validat =' => 1])
                    ->andWhere(['date >=' => $lundi])
                    ->andWhere(['date <' => $dimanche])
                    ->contain(['Projet' => ['Client']])->all();
            $buff = array();
            foreach ($arrayTemps as $temps) {
                $buff[$temps->n_ligne][] = $temps;
            }
            $retour = $this->getDaysInWeek($buff, $lundi, $dimanche, $userAll->idu);
            $week[$userAll->idu] = $retour[0];
        }

        $validat = false;
        $exportableTable = TableRegistry::get('Exportable');
        $isLocked = $exportableTable->find('all')->where(['n_sem =' => $semaine, 'annee =' => $annee ])->first();
        if (!is_null($isLocked)) {
            $validat = true;
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();

            $arrayIdCurrent = array();
            $entities = array();
            $verif = true;
            $arrayIdentifierLine = array();
            if (array_key_exists('day', $arrayData)) {
                foreach ($arrayData['day'] as $idUser => $arrayLine) {
                    foreach ($arrayLine as $line => $arrayDay) {
                        $dayTime = clone $lundi;
                        $identifierLine = $arrayData['users'][$idUser][$line] . $arrayData['client'][$idUser][$line] .
                            $arrayData['projet'][$idUser][$line] . $arrayData['profil'][$idUser][$line] . $arrayData['activities'][$idUser][$line] ;
                        if (in_array($identifierLine, $arrayIdentifierLine)) {
                            $this->Flash->error(__('Duplication de ligne, veuilez contrôler votre saisie avant de réessayer.'));
                            $verif = false;
                        }
                        $arrayIdentifierLine[] = $identifierLine;
                        foreach ($arrayDay as $dataDay) {
                            $idu = $arrayData['users'][$idUser][$line];
                            $arrayIdc = explode('.',$arrayData['client'][$idUser][$line]);
                            $arrayIdp = explode('.',$arrayData['projet'][$idUser][$line]);
                            $arrayIdprof = explode( '.', $arrayData['profil'][$idUser][$line]);
                            $arrayIda = explode('.', $arrayData['activities'][$idUser][$line]);
                            if (empty($dataDay['time'])) {
                                $dayTime->modify('+1 days');
                                continue;
                            }
                            if ($dataDay['time'] > 1 && $verif) {
                                $this->Flash->error(__('La saisie journalière ne peux dépasser une journée sur un même projet'));
                                $verif = false;
                            }
                            if ($idu==$arrayIdc[0] && $idu==$arrayIdp[0]
                            && $arrayIdc[1]==$arrayIdp[1] && $arrayIdc[1]==$arrayIdprof[0] && $arrayIdp[2]==$arrayIda[0]) {
                                $day = null;
                                if (empty($dataDay['id'])) {
                                    $day = $this->Temps->newEntity();
                                    $day->validat = 1;
                                }else{
                                    // A optimiser si besoin
                                    $day = $this->Temps->get($dataDay['id'], [ 'contain' => [] ]);
                                    // FIN optimisation
                                    $arrayIdCurrent[] = $dataDay['id'];
                                }
                                $day->idu = $idUser;
                                $day->date = clone $dayTime ;
                                $day->n_ligne = $line;
                                $day->time = $dataDay['time'];
                                $day->validat = 1;
                                $day->idp = $arrayIdp[2];
                                $day->id_profil = $arrayIdprof[1];
                                $day->ida = $arrayIda[1];
                                $entities[] = $day;
                                // add to $week to keep the data in case of error and redirect in the same page
                                $week[$idUser][$line]['idc'] = $arrayData['client'][$idUser][$line];
                                $week[$idUser][$line]['idp'] = $arrayData['projet'][$idUser][$line];
                                $week[$idUser][$line]['id_profil'] = $arrayData['profil'][$idUser][$line];
                                $week[$idUser][$line]['ida'] = $arrayData['activities'][$idUser][$line];
                                $week[$idUser][$line][$this->getDay($day->date, $lundi)] = $day;
                                $week[$idUser][$line]['nline'] = $line;

                                $dayTime->modify('+1 days');
                            }
                        }
                    }
                }
            }
            if ($verif) {
                //Deletion
                if (is_null($isLocked)) {
                    $query = $this->Temps->find('all')
                        ->where(['validat =' => 1,
                         'date >=' => $lundi,
                         'date <' => $dimanche]);
                    if (!empty($arrayIdCurrent)) {
                        $query->andWhere(['idt NOT IN' => $arrayIdCurrent]);
                    }
                    $listDeletion = $query->toArray();
                    if (!empty($listDeletion)) {
                        foreach ($listDeletion as  $entity) {
                            $verif = $verif && $this->Temps->delete($entity);
                        }
                    }
                    //Save
                    if (!empty($entities)) {
                        foreach ($entities as $day) {
                            try {
                                $this->Temps->saveOrFail($day);
                            } catch (\Cake\ORM\Exception\PersistenceFailedException $e) {
                                $oldDay = $this->Temps->find('all')->where([ 'idu =' => $day->idu,
                                    'idp =' => $day->idp, 'id_profil =' => $day->id_profil,
                                    'ida =' => $day->ida, 'date =' => $day->date])->first();

                                if (!is_null($oldDay)) {
                                    $oldDay->time = $day->time;
                                    $oldDay->n_ligne = $day->n_ligne;
                                    $oldDay->validat = $day->validat;
                                    $verif = $verif && $this->Temps->save($oldDay);
                                }else{
                                    $verif = false;
                                }
                            }
                        }
                    }
                }
                if ($arrayData['validat'] == 0 && !is_null($isLocked)) {
                    $exportableTable->delete($isLocked);
                }elseif ($arrayData['validat'] == 1 && is_null($isLocked)) {
                    $locked = $exportableTable->newEntity();
                    $locked->n_sem = $semaine;
                    $locked->annee = $annee;
                    $exportableTable->save($locked);
                }
            }
            if ($verif) {
                $this->Flash->success(__('La semaine à été sauvegardé.'));

                return $this->redirect(['controller'=>'Board', 'action' => 'index']);
            }else{
                $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
            }
        }

        $arrayBuff=array();
        foreach ($week as $idu => $weekUser) {
            $week[$idu] = $this->autoCompleteWeek($weekUser);

            $arrayBuff = $this->getProjects($idu, $lundi, $dimanche);
            $arrayRetour['projets']   = array_merge($arrayRetour['projets'], $arrayBuff['projets']);
            $arrayRetour['clients']   = array_merge($arrayRetour['clients'], $arrayBuff['clients']);
            $arrayRetour['profiles']  = array_merge($arrayRetour['profiles'], $arrayBuff['profiles']);
            $arrayRetour['activities']= array_merge($arrayRetour['activities'], $arrayBuff['activities']);
        }
        $fullNameUserAuth = $user->fullname;

        // $this->set(compact('temps'));
        $this->set(compact('week'));
        $this->set(compact('semaine'));
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
        $this->set('controller', false);
    }

    private function getDaysInWeek($buff, $lundi, $dimanche, $idu)
    {
        $week = array();
        $validat = false;
        foreach ($buff as $key => $arrayDays) {
            foreach ($arrayDays as $day) {
                $week[$key]['idc'] = $idu.'.'.$day->projet->idc;
                $week[$key]['idp'] = $idu.'.'.$day->projet->idc.'.'.$day->idp;
                $week[$key]['id_profil'] = $day->projet->idc.'.'.$day->id_profil;
                $week[$key]['ida'] = $day->idp.'.'.$day->ida;
                if (!$validat) {
                    $validat=$day->validat;
                }
                $week[$key][$this->getDay($day->date, $lundi)] = $day;
                $week[$key]['nline'] = $day->n_ligne;
            }
        }
        return [$week, $validat];
    }

    private function autoCompleteWeek($week) {
        $modelWeek = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
        foreach ($week as $key => $arrayDays) {
            foreach ($modelWeek as $idDay) {
                if (!array_key_exists($idDay, $week[$key]) ) {
                    $week[$key][$idDay] = $this->Temps->newEntity();
                }
            }
        }
        return $week;
    }

    private function getDay($date, $lundi)
    {
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
        if ($date >=  $lundi
        && $date <  $mardi) {
            return 'Lu';
        }elseif($date >=  $mardi
        && $date <  $mercredi) {
            return 'Ma';
        }elseif($date >=  $mercredi
        && $date <  $jeudi) {
            return 'Me';
        }elseif($date >=  $jeudi
        && $date <  $vendredi) {
            return 'Je';
        }elseif($date >=  $vendredi
        && $date <  $samedi) {
            return 'Ve';
        }elseif($date >=  $samedi
        && $date <  $dimanche) {
            return 'Sa';
        }else {
            return 'Di';
        }
    }

    private function getProjects($idu, $lundi, $dimanche)
    {
        $participantTable = TableRegistry::get('Participant');
        $activitiesTable = TableRegistry::get('Activities');
        $arrayProjects = array();
        $arrayRetour = array('projets'=>[], 'clients'=>[], 'profiles'=>[], 'activities'=>[]);
        $particpations = $participantTable->find('all')
            ->where(['idu =' => $idu])
            ->andWhere(['date_debut <' => $dimanche->i18nFormat('YYYY-MM-dd')])
            ->andWhere(['date_fin >=' => $lundi->i18nFormat('YYYY-MM-dd')])
            ->contain(['Projet' => ['Client'=>['Matrice'=>['LignMat'=>['Profil']]]]]);//->all();
                    pr($dimanche);
                    pr($lundi);
                    pr($particpations);
                    pr($particpations->all());exit;
        foreach ($particpations as $participant) {
            $projet = $participant->projet;
            $arrayProjects[$idu . '.' . $projet->idc . '.' . $projet->idp] = $projet;
            $arrayRetour['projets'][$idu . '.' . $projet->idc . '.' . $projet->idp] = $projet->nom_projet;
        }
        $arrayClients = array();
        foreach ($arrayProjects as $projet) {
            $arrayClients[$idu . '.' . $projet->idc] = $projet->client;
            $arrayRetour['clients'][$idu . '.' . $projet->idc] = $projet->client->nom_client;


            $activities = $activitiesTable->findByIdp($projet->idp)->contain(['Activitie'])->all();
            foreach ($activities as $activity) {
                $arrayRetour['activities'][$projet->idp . '.' . $activity->ida] = $activity->activitie->nom_activit;
            }
        }
        foreach ($arrayClients as $client) {
            foreach ($client->matrice->lign_mat as $ligne) {
                $arrayRetour['profiles'][$client->idc . '.' . $ligne->id_profil] = $ligne->profil->nom_profil;
            }
        }

        return $arrayRetour;
    }

    public function export(){
        $export = new ExportForm();
        $clientTable = TableRegistry::get('Client');
        $arrayClient = $clientTable->find('all')->toArray();
        $clients = array();
        foreach ($arrayClient as $client) {
            $clients[$client->idc] = ucfirst($client->nom_client);
        }
        $userTable = TableRegistry::get('Users');
        $arrayUser = $userTable->find('all')->toArray();
        $users = array();
        foreach ($arrayUser as $user) {
            $users[$user->idu] = $user->fullname;
        }
        if ($this->request->is(['post'])) {
            $arrayData = $this->request->getData();
            $isValid = $export->validate($arrayData);
            if ($isValid){
                $arrayData['date_debut'] = Time::parse($arrayData['date_debut']);
                $arrayData['date_fin'] = Time::parse($arrayData['date_fin']);

                $data = array();
                $periodes = array();
                $exportableTable = TableRegistry::get('Exportable');
                $semaineDebut = (int)date('W', strtotime($arrayData['date_debut']->i18nFormat('dd-MM-YYYY')));
                $anneeDebut = (int)date('Y', strtotime($arrayData['date_debut']->i18nFormat('dd-MM-YYYY')));
                $semaineFin = (int)date('W', strtotime($arrayData['date_fin']->i18nFormat('dd-MM-YYYY')));
                $anneeFin = (int)date('Y', strtotime($arrayData['date_fin']->i18nFormat('dd-MM-YYYY')));
                $arraNSem = array($anneeDebut => array());
                $y=$anneeDebut;
                for ($i=$semaineDebut; ($i <= $semaineFin || $y < $anneeFin) ; $i++) {
                    if ($i > 52) {
                        $i = 1;
                        $y++;
                    }
                    $arraNSem[$y][] = $i;
                }
                $query = null;
                $query = $exportableTable->find('all');
                $andWhere = array();
                foreach ($arraNSem as $an => $sem) {
                    if (!empty($sem)) {
                        $andWhere[] = ['n_sem IN' => $sem, 'annee =' => $an];
                    }
                }
                $query->where(['OR' => $andWhere]);
                $periodes = $query->toArray();

                $andWhere = array();
                if (!empty($periodes)) {
                    foreach ($periodes as $periode) {
                        $lundi = new Date('now');
                        $lundi->setTime(00, 00, 00);
                        $lundi->setISOdate($periode->annee, $periode->n_sem);
                        $dimanche = clone $lundi;
                        $dimanche->modify('+7 days');

                        $andWhere[] = [ 'date >=' => $lundi,
                                        'date <' => $dimanche,
                                    ];
                    }
                    $query = null;
            		$query = $this->Temps->find('all')
                        ->where(['date >=' => $arrayData['date_debut'], 'date <=' => $arrayData['date_fin'], 'validat =' => 1])
                        ->andwhere(['OR' => $andWhere]);
                    if (!empty($arrayData['client'])) {
                        $exportableTable = TableRegistry::get('Projet');
                        $arrayIdProjet = $exportableTable->find('list',['fields' =>['Projet.idc', 'Projet.idp']])->where(['idc =' => $arrayData['client']])->toArray();
                        $query->andWhere(['idp IN' => $arrayIdProjet]);
                    }
                    if (!empty($arrayData['user']) ){
                        $query->andWhere(['idu =' => $arrayData['user']]);
                    }
                    $times = $query->toArray();
                }
                if (empty($times)) {
                    $this->Flash->error("Aucune saisie valide trouvé pour la période demandé.");
                }else{
                    $data = $this->getDataFromTimes($times, $users, $clients, $arrayData['fitnet']);
                    // pr($data);exit;
                    if ($arrayData['fitnet']) {
                        $title = 'export_fitnet';
                    }else{
                        $title = 'export';
                    }
            		$this->response->download($title.'.csv');
                    $arrayMonth = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'novembre', 'Décembre'];
                    $arrayMonthBuffer = array_merge($arrayMonth, $arrayMonth);
                    $arrayMonthBuffer = array_merge($arrayMonthBuffer, $arrayMonth);
                    $_header = array_merge(['Client', 'Projet', 'Consultant', 'Profil', 'Activités'], $arrayMonthBuffer);
            		$_serialize = 'data';
                    $_delimiter = ';';
               		$this->set(compact('data', '_serialize', '_delimiter', '_header'));
            		$this->viewBuilder()->className('CsvView.Csv');
            		return;
                }
            }else{
                $this->Flash->error("Une erreur est survenu. Merci de vérifier la saisie ou de retenter ultérieurement");
            }

        }
        asort($clients);
        asort($users);
        $this->set(compact('export'));
        $this->set(compact('clients'));
        $this->set(compact('users'));
    }

    public function getDataFromTimes($times=array(), $users = array(), $clients = array(), $isFitnet = false)
    {
        $projetTable = TableRegistry::get('Projet');
        $arrayprojects = $projetTable->find('all', ['fields'=>['idp','idc', 'nom_projet']])->toArray();
        $projects = $projectClients = array();
        foreach ($arrayprojects as $proj) {
            $projects[$proj->idp] = $proj->nom_projet;
            $projectClients[$proj->idp] = $proj->idc;
        }
        $profilTable = TableRegistry::get('Profil');
        $profils = $profilTable->find('list', ['fields'=>['id_profil', 'nom_profil']])->toArray();
        $activitTable = TableRegistry::get('Activitie');
        $activits = $activitTable->find('list', ['fields'=>['ida', 'nom_activit']])->toArray();

        $clientTable = TableRegistry::get('Client');
        $arrayClientMatrice = $clientTable->find('all')->contain(['Matrice'=>['LignMat']])->toArray();
        $arrayMatrice = array();
        $arrayClientPrice = array();
        foreach ($arrayClientMatrice as $client) {
            $arrayClientPrice[ucfirst($client->nom_client)]= $client->prix;
            foreach ($client->matrice->lign_mat as $lign_mat) {
                $arrayMatrice[ucfirst($client->nom_client)][$profils[$lign_mat->id_profil]]['h'] = $lign_mat->heur;
                $arrayMatrice[ucfirst($client->nom_client)][$profils[$lign_mat->id_profil]]['j'] = $lign_mat->jour;
            }
        }

        $data = array();
        if (empty($times) || !is_array($times)) {
            return $data;
        }
        foreach ($times as $time) {
            $keyClient = $clients[$projectClients[$time->idp]];
            $keyProject = $projects[$time->idp];
            $keyUser = $users[$time->idu];
            $keyProfil = $profils[$time->id_profil];
            $keyActivit = $activits[$time->ida];
            if (!array_key_exists($keyClient, $data)) {
                $data[$keyClient] = array();
            }
            if (!array_key_exists($keyProject, $data[$keyClient])) {
                $data[$keyClient][$keyProject] = array();
            }
            if (!array_key_exists($keyUser, $data[$keyClient][$keyProject])) {
                $data[$keyClient][$keyProject][$keyUser] = array();
            }
            if (!array_key_exists($keyProfil, $data[$keyClient][$keyProject][$keyUser])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil] = array();
            }
            if (!array_key_exists($keyActivit, $data[$keyClient][$keyProject][$keyUser][$keyProfil])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit] = array();
            }
            if ($isFitnet) {
                $keyDate = $time->date->i18nFormat('YYYY-MM-dd');
            }else{
                $keyDate = $time->date->i18nFormat('YYYY-MM');
            }
            if (!array_key_exists($keyDate, $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate] = array('JH'=>0, 'UO'=>0, 'CA'=>0);
            }
            if ($time->time == 1) {
                $timeUO =  $arrayMatrice[$keyClient][$keyProfil]['j'];
            }else{
                $timeUO = round($time->time * 8, 1) * $arrayMatrice[$keyClient][$keyProfil]['h'];
            }
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate]['JH']+=$time->time;
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate]['UO']+=$timeUO;
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate]['CA']+=$timeUO;

            ksort($data[$keyClient]);
            ksort($data[$keyClient][$keyProject]);
            ksort($data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit]);
        }
        ksort($data);
        $dataLine=array();
        if ($isFitnet) {
            //@TODO mise en page pour fitnet
        }else{
            $arrayMonth = ['', '', '', '', '', '', '', '', '', '', '', ''];
            foreach ($data as $client => $arrProj) {
                foreach ($arrProj as $projet => $arrUser) {
                    foreach ($arrUser as $user => $arrProfil) {
                        foreach ($arrProfil as $profil => $arrActiv) {
                            foreach ($arrActiv as $activit => $arrDate) {
                                $buffer = ['client'=>$client, 'projet'=>$projet, 'user'=>$user, 'profil'=>$profil,'activit'=>$activit];
                                $timebuffer = [];
                                $timebufferMonth = $arrayMonth;
                                $UobufferMonth = $arrayMonth;
                                $CabufferMonth = $arrayMonth;
                                foreach ($arrDate as $date => $arrTime) {
                                    foreach ($arrTime as $type => $time) {
                                        $monthKey = explode('-',$date)[1] -1;
                                        switch ($type) {
                                            case 'UO':
                                                $UobufferMonth[$monthKey] = $time;
                                                break;
                                            case 'CA':
                                                $CabufferMonth[$monthKey] = ($time*$arrayClientPrice[$client]);
                                                break;
                                            default:
                                                $timebufferMonth[$monthKey] = $time;
                                                break;
                                        }
                                    }
                                }
                                $timebufferMonth = array_merge($timebufferMonth, $UobufferMonth);
                                $timebufferMonth = array_merge($timebufferMonth, $CabufferMonth);
                                $timebuffer = array_merge($timebuffer, $timebufferMonth);
                                $dataLine[] = array_merge($buffer, $timebuffer);
                            }
                        }
                    }
                }
            }
        }
        return $dataLine;
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['indexAdmin', 'export']) && $user['admin'] === 1 ) {
            return true;
        }

        if (in_array($action, ['index']) ) {
            return true;
        }

        return false;
    }
}
