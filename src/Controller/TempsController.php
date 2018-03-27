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
                $clientTable = TableRegistry::get('Client');
                foreach ($arrayData['day'] as $line => $arrayDay) {
                    $dayTime = clone $lundi;
                    $identifierLine = (string) $arrayData['client'][$line] . $arrayData['projet'][$line] . $arrayData['profil'][$line] . $arrayData['activities'][$line] ;
                    if (in_array($identifierLine, $arrayIdentifierLine)) {
                        $this->Flash->error(__('Duplication de ligne, veuilez contrôler votre saisie avant de réessayer.'));
                        $verif = false;
                    }
                    if ($arrayData['client'][$line] == 0 || $arrayData['projet'][$line] == 0 || $arrayData['projet'][$line] == 0
                     || $arrayData['profil'][$line] == 0 || $arrayData['profil'][$line] == 0 || $arrayData['activities'][$line] == 0) {
                        continue;
                    }
                    $arrayIdentifierLine[] = $identifierLine;
                    foreach ($arrayDay as $dataDay) {
                        $idc =explode('.',$arrayData['client'][$line])[1];
                        $arrayIdp = explode('.',$arrayData['projet'][$line]);
                        $arrayIdprof = explode( '.', $arrayData['profil'][$line]);
                        $arrayIda = explode('.', $arrayData['activities'][$line]);
                        //generate day
                        $day = null;
                        if (empty($dataDay['id'])) {
                            $day = $this->Temps->newEntity();
                            $day->idu = $user->idu;
                        }else{
                            $day = $this->Temps->get($dataDay['id'], [ 'contain' => [] ]);
                            $arrayIdCurrent[] = $dataDay['id'];
                        }
                        $day->time = $dataDay['time'];
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
                        if ($dataDay['time'] > 1 && $verif) {
                            $this->Flash->error(__('La saisie journalière ne peux dépasser une journée pleine sur un même projet avec les mêmes rôles'));
                            $verif = false;
                        }
                        if ($idc==$arrayIdp[1] && $idc==$arrayIdprof[0] && $arrayIdp[2]==$arrayIda[0]) {
                            $client  = $clientTable->get($idc);
                            //For deletion
                            if ($day->idt) {
                                $arrayIdCurrent[] = $dataDay['id'];
                            }

                            $day->date = clone $dayTime ;
                            $day->n_ligne = $line;
                            $day->time = $dataDay['time'];
                            $day->validat = $arrayData['validat'];
                            $day->idp = $arrayIdp[2];
                            $day->id_profil = $arrayIdprof[1];
                            $day->ida = $arrayIda[1];
                            $day->idm = $client->idm;
                            $day->prix = $client->prix;
                            $day->detail = $arrayData['detail'][$line];
                            $entities[] = $day;

                            $dayTime->modify('+1 days');
                        }
                    }
                }
            }
            if ($verif) {
                //Deletion
                $query = $this->Temps->find('all')
                    ->where(['idu =' => $user->idu,
                     'date >=' => $lundi,
                     'date <' => $dimanche]);
                if (!empty($arrayIdCurrent)) {
                    $query->andWhere(['idt  NOT IN' => $arrayIdCurrent]);
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
                return $this->redirect(['action' => 'index']);
            }else{
                $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
            }
        }

        $week = $this->autoCompleteWeek($week);

        $arrayRetour =  array();
        $arrayEmpty = ['0'=>'-'];
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche);
        asort($arrayRetour['projets']);
        asort($arrayRetour['clients']);
        asort($arrayRetour['profiles']);
        asort($arrayRetour['activities']);
        $fullNameUserAuth = $user->fullname;

        $this->set(compact('week'));
        $this->set(compact('semaine'));
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
        $arrayRetour = array('users' => ['0'=>'-'], 'projets' => ['0'=>'-'], 'clients' => ['0'=>'-'], 'profiles' => ['0'=>'-'], 'activities' => ['0'=>'-']);
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
                $clientTable = TableRegistry::get('Client');
                foreach ($arrayData['day'] as $idUser => $arrayLine) {
                    foreach ($arrayLine as $line => $arrayDay) {
                        $dayTime = clone $lundi;
                        $identifierLine = $arrayData['users'][$idUser][$line] . $arrayData['client'][$idUser][$line] .
                            $arrayData['projet'][$idUser][$line] . $arrayData['profil'][$idUser][$line] . $arrayData['activities'][$idUser][$line] ;
                        if (in_array($identifierLine, $arrayIdentifierLine)) {
                            $this->Flash->error(__('Duplication de ligne, veuilez contrôler votre saisie avant de réessayer.'));
                            $verif = false;
                        }
                        if ($arrayData['users'][$idUser][$line] == 0 || $arrayData['client'][$idUser][$line] == 0 || $arrayData['projet'][$idUser][$line] == 0
                         || $arrayData['projet'][$idUser][$line] == 0 || $arrayData['profil'][$idUser][$line] == 0 || $arrayData['profil'][$idUser][$line] == 0
                         || $arrayData['activities'][$idUser][$line] == 0) {
                            continue;
                        }
                        $arrayIdentifierLine[] = $identifierLine;
                        foreach ($arrayDay as $dataDay) {
                            $idu = $arrayData['users'][$idUser][$line];
                            $arrayIdc = explode('.',$arrayData['client'][$idUser][$line]);
                            $arrayIdp = explode('.',$arrayData['projet'][$idUser][$line]);
                            $arrayIdprof = explode( '.', $arrayData['profil'][$idUser][$line]);
                            $arrayIda = explode('.', $arrayData['activities'][$idUser][$line]);
                            //Generate Day
                            $day = null;
                            if (empty($dataDay['id'])) {
                                $day = $this->Temps->newEntity();
                                $day->validat = 1;
                            }else{
                                // A optimiser si besoin
                                $day = $this->Temps->get($dataDay['id'], [ 'contain' => [] ]);
                                // FIN optimisation
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

                            if (empty($dataDay['time']) || $dataDay['time'] <= 0) {
                                $dayTime->modify('+1 days');
                                continue;
                            }
                            if ($idu==$arrayIdc[0] && $idu==$arrayIdp[0]
                            && $arrayIdc[1]==$arrayIdp[1] && $arrayIdc[1]==$arrayIdprof[0] && $arrayIdp[2]==$arrayIda[0]) {
                                $client  = $clientTable->get($arrayIdc[1]);
                                //For deletion
                                if ($day->idt) {
                                    $arrayIdCurrent[] = $dataDay['id'];
                                }
                                $day->idu = $idUser;
                                $day->date = clone $dayTime ;
                                $day->n_ligne = $line;
                                $day->validat = 1;
                                $day->idp = $arrayIdp[2];
                                $day->id_profil = $arrayIdprof[1];
                                $day->ida = $arrayIda[1];
                                $day->idm = $client->idm;
                                $day->prix = $client->prix;
                                $day->detail = $arrayData['detail'][$idUser][$line];
                                $entities[] = $day;

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
        asort($arrayRetour['users']);
        asort($arrayRetour['projets']);
        asort($arrayRetour['clients']);
        asort($arrayRetour['profiles']);
        asort($arrayRetour['activities']);
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
                $week[$key]['detail'] = $day->detail;
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
            ->andWhere(['date_debut <' => $dimanche->year.$dimanche->i18nFormat('-MM-dd')])
            ->andWhere(['date_fin >=' => $lundi->year.$lundi->i18nFormat('-MM-dd')])
            ->contain(['Projet' => ['Client'=>['Matrice'=>['LignMat'=>['Profil']]]]])->all();
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

    public function getProjectName($id){
        $projetTable = TableRegistry::get('Projet');
        $idp = explode('.', $id)[2];
        $project = $projetTable->get($idp);
        return $this->response->withStringBody($project->nom_projet);
    }

    public function getClientName($id){
        $clientTable = TableRegistry::get('Client');
        $idc = explode('.', $id)[1];
        $client = $projetTable->get($idc);
        return $this->response->withStringBody($client->nom_client);
    }

    public function getProfilName($id){
        $profilTable = TableRegistry::get('Profil');
        $idprof = explode('.', $id)[1];
        $profil = $profilTable->get($idprof);
        return $this->response->withStringBody($profil->nom_profil);
    }

    public function getActivitieName($id){
        $actTable = TableRegistry::get('Activitie');
        $ida = explode('.', $id)[1];
        $act = $actTable->get($ida);
        return $this->response->withStringBody($act->nom_activit);
    }

    private function clearDtb(){
        $currentYear = new Date('Now');
        $currentYear->modify('-2 years');
        $query = $this->Temps->find('all')
            ->where(['date <=' => $currentYear->year.'-01-01']);
        $listDeletion = $query->toArray();
        if (!empty($listDeletion)) {
            foreach ($listDeletion as $entity) {
                $this->Temps->delete($entity);
            }
        }
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
            // $this->clearDtb();
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
                        $ProjetTable = TableRegistry::get('Projet');
                        $arrayIdProjet = $ProjetTable->find('list',['fields' =>['idc','idp']])->where(['idc =' => $arrayData['client']])->toArray();
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
                    if ($arrayData['fitnet']) {
                        $title = 'export_fitnet';
                    }else{
                        $title = 'export';
                    }
            		$this->response->download($title.'.csv');
                    $arrayMonth = ['JH Janvier', 'JH Février', 'JH Mars', 'JH Avril', 'JH Mai', 'JH Juin', 'JH Juillet', 'JH Août', 'JH Septembre', 'JH Octobre', 'JH novembre', 'JH Décembre'];
                    $arrayMonthUO = ['UO Janvier', 'UO Février', 'UO Mars', 'UO Avril', 'UO Mai', 'UO Juin', 'UO Juillet', 'UO Août', 'UO Septembre', 'UO Octobre', 'UO novembre', 'UO Décembre'];
                    $arrayMonthCA = ['CA Janvier', 'CA Février', 'CA Mars', 'CA Avril', 'CA Mai', 'CA Juin', 'CA Juillet', 'CA Août', 'CA Septembre', 'CA Octobre', 'CA novembre', 'CA Décembre'];
                    $arrayMonthBuffer = array_merge($arrayMonth, $arrayMonthUO);
                    $arrayMonthBuffer = array_merge($arrayMonthBuffer, $arrayMonthCA);
                    $_header = array_merge(['Client', 'Projet', 'Consultant', 'Profil', 'Activités','Détails'], $arrayMonthBuffer);
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
        $this->set('controller', false);
    }

    private function getDataFromTimes($times=array(), $users = array(), $clients = array(), $isFitnet = false)
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
            $dateTime = $time->date;
            if ($isFitnet) {
                $keyDate = $dateTime->year.'-'.$dateTime->month.'-'.$dateTime->day;
            }else{
                $keyDate = $dateTime->year.'-'.$dateTime->month;
            }
            if (!array_key_exists($keyDate, $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit])) {
                $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate] = array('JH'=>0, 'UO'=>0, 'CA'=>0);
            }
            if ($time->time == 1) {
                $timeUO =  $arrayMatrice[$time->idm][$keyProfil]['j'];
            }else{
                $timeUO = round($time->time * 8, 1) * $arrayMatrice[$time->idm][$keyProfil]['h'];
            }
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate]['JH']+=$time->time;
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate]['UO']+=$timeUO;
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit][$keyDate]['CA']+=$timeUO*$time->prix;
            $data[$keyClient][$keyProject][$keyUser][$keyProfil][$keyActivit]['detail']=$time->detail;

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
                                $timebuffer = array();
                                $arrayYear = array();
                                foreach ($arrDate as $date => $arrTime) {
                                    if (!is_array($arrTime)) {
                                        $buffer['detail']=$arrTime;
                                        continue;
                                    }
                                    foreach ($arrTime as $type => $time) {
                                        $yearKey = explode('-',$date)[0];
                                        if (!in_array($yearKey, $arrayYear)) {
                                            $arrayYear[] = $yearKey;
                                            $timebufferMonth[$yearKey] = $arrayMonth;
                                            $UobufferMonth[$yearKey] = $arrayMonth;
                                            $CabufferMonth[$yearKey] = $arrayMonth;
                                        }
                                        $monthKey = explode('-',$date)[1] -1;
                                        switch ($type) {
                                            case 'UO':
                                                $UobufferMonth[$yearKey][$monthKey] = $time;
                                                break;
                                            case 'CA':
                                                $CabufferMonth[$yearKey][$monthKey] = $time;
                                                break;
                                            default:
                                                $timebufferMonth[$yearKey][$monthKey] = $time;
                                                break;
                                        }
                                    }
                                }
                                sort($arrayYear);
                                foreach ($arrayYear as $yearValue) {
                                    $timebufferMonth[$yearValue] = array_merge($timebufferMonth[$yearValue], $UobufferMonth[$yearValue]);
                                    $timebufferMonth[$yearValue] = array_merge($timebufferMonth[$yearValue], $CabufferMonth[$yearValue]);
                                    $timebuffer = array_merge($timebuffer, $timebufferMonth[$yearValue]);
                                }
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

        if (in_array($action, ['export']) && $user['role'] >= 20 ) {
            return true;
        }

        if (in_array($action, ['indexAdmin']) && $user['role'] >= 50 ) {
            return true;
        }

        if (in_array($action, ['index', 'getProjectName', 'getClientName', 'getProfilName', 'getActivitieName']) ) {
            return true;
        }

        return false;
    }
}
