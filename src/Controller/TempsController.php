<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\FrozenTime;
use Cake\I18n\Date;
use Cake\ORM\TableRegistry;

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
        $lundi->setISOdate($annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+6 days');

        $usersTable = TableRegistry::get('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $usersTable->findByIdu($idUserAuth, [
            'contain' => []
        ])->firstOrFail();

        $arrayTemps = $this->Temps->find('all')
                ->where(['idu =' => $idUserAuth])
                ->andWhere(['date >=' => $lundi->i18nFormat('YYYY-MM-dd 00:00:00')])
                ->andWhere(['date <=' => $dimanche->i18nFormat('YYYY-MM-dd 23:59:59')])
                ->contain(['Projet' => ['Client']])
                ->all();

        $buff = array();

        if ($this->request->is(['patch', 'post', 'put'])) {
            $arrayData = $this->request->getData();
            $arrayIdCurrent = array();
            $entities = array();
            $verif = true;
            foreach ($arrayData['day'] as $line => $arrayDay) {
                $dayTime = clone $lundi;
                foreach ($arrayDay as $dataDay) {
                    $idc = $arrayData['client'][$line];
                    $arrayIdp = explode($arrayData['projet'][$line], '.');
                    $arrayIdprof = explode($arrayData['profil'][$line], '.');
                    $arrayIda = explode($arrayData['activities'][$line], '.');
                    if (empty($dataDay['time'])) {
                        continue;
                    }
                    if ($dataDay['time'] <= 1) {
                        $this->Flash->error(__('La saisie journalière ne peux dépasser une journée sur un même projet'));
                        $verif = false;
                        break(2);
                    }
                    if ($idc==$arrayIdp[0] && $idc==$arrayIdprof[0] && $arrayIdp[1]==$arrayIda[0]) {
                        $day = null;
                        if (empty($dataDay['id'])) {
                            $day = $this->Time->newEntity();
                            $day->idu = $user->idu;
                        }else{
                            $day = $this->Temps->get($dataDay['id'], [ 'contain' => [] ]);
                            $arrayIdCurrent[] = $dataDay['id'];
                        }
                        $day->date = clone $dayTime;
                        $day->n_ligne = $line;
                        $day->time = $dataDay['time'];
                        $day->lock = $arrayData['lock'];
                        $day->idc = $idc;
                        $day->idp = $arrayIdp[1];
                        $day->id_profil = $arrayIdprof[1];
                        $day->ida = $arrayIda[1];
                        $entities[] = $day;
                        $buff[$line][]=$day;
                        $dayTime->modify('+1 days');
                    }
                }

            }
            foreach ($entities as $day) {
                $verif = $verif && $this->Temps->save($day);
            }
            // @TODO : Suppression des anciens jours non in $arrayIdCurrent
            if ($verif) {
                $this->Flash->success(__('La semaine à été sauvegardé.'));

                return $this->redirect(['controller'=>'Board', 'action' => 'index']);
            }else{
                $this->Flash->error(__('Une erreur est survenue, veuilez contrôler votre saisie avant de réessayer.'));
            }

        }

        foreach ($arrayTemps as $temps) {
            $buff[$temps->n_ligne][] = $temps;
        }

        $retour = $this->getDaysInWeek($buff, $lundi, $dimanche);
        $week = $retour[0];
        $lock = $retour[1];

        $arrayRetour = $projects = $clients = $profilMatrices = array();
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche);
        $fullNameUserAuth = $user->fullname;

        // $this->set(compact('temps'));
        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('lock'));
        $this->set('projects', $arrayRetour['projets']);
        $this->set('clients', $arrayRetour['clients']);
        $this->set('profiles', $arrayRetour['profiles']);
        $this->set('activities', $arrayRetour['activities']);
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
        $lundi->setISOdate($annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+6 days');

        $usersTable = TableRegistry::get('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $usersTable->findByIdu($idUserAuth, [
            'contain' => []
        ])->firstOrFail();

        $arrayTemps = $this->Temps->find('all')
                ->where(['idu =' => $idUserAuth])
                ->andWhere(['date >=' => $lundi->i18nFormat('YYYY-MM-dd 00:00:00')])
                ->andWhere(['date <=' => $dimanche->i18nFormat('YYYY-MM-dd 23:59:59')])
                ->contain(['Projet' => ['Client']])
                ->all();

        $buff = array();
        foreach ($arrayTemps as $temps) {
            $buff[$temps->n_ligne][] = $temps;
        }

        $retour = $this->getDaysInWeek($buff, $lundi, $dimanche);
        $week = $retour[0];
        $lock = $retour[1];

        if ($this->request->is(['patch', 'post', 'put'])) {
            pr($arrayData);exit;
            // @TODO : controle sur time > 1

        }
        $arrayRetour = $projects = $clients = $profilMatrices = array();
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche);
        $fullNameUserAuth = $user->fullname;

        // $this->set(compact('temps'));
        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set(compact('lock'));
        $this->set('controller','lock');
        $this->set('projects', $arrayRetour['projets']);
        $this->set('clients', $arrayRetour['clients']);
        $this->set('profiles', $arrayRetour['profiles']);
        $this->set('activities', $arrayRetour['activities']);
    }

    private function getDaysInWeek($buff, $lundi, $dimanche)
    {
        $week = array();
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
        $lock = false;
        $modelWeek = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
        foreach ($buff as $key => $arrayDays) {
            foreach ($arrayDays as $day) {
                $week[$key]['idc'] = $day->projet->idc;
                $week[$key]['idp'] = $day->projet->idc.'.'.$day->idp;
                $week[$key]['id_profil'] = $day->projet->idc.'.'.$day->id_profil;
                $week[$key]['ida'] = $day->idp.'.'.$day->ida;
                if (!$lock) {
                    $lock=$day->lock;
                }
                if ($day->date >=  $lundi
                && $day->date <  $mardi) {
                    $week[$key]['Lu'] = $day;
                }elseif($day->date >=  $mardi
                && $day->date <  $mercredi) {
                    $week[$key]['Ma'] = $day;
                }elseif($day->date >=  $mercredi
                && $day->date <  $jeudi) {
                    $week[$key]['Me'] = $day;
                }elseif($day->date >=  $jeudi
                && $day->date <  $vendredi) {
                    $week[$key]['Je'] = $day;
                }elseif($day->date >=  $vendredi
                && $day->date <  $samedi) {
                    $week[$key]['Ve'] = $day;
                }elseif($day->date >=  $samedi
                && $day->date <  $dimanche) {
                    $week[$key]['Sa'] = $day;
                }else {
                    $week[$key]['Di'] = $day;
                }
            }
        }
        foreach ($week as $key => $arrayDays) {
            foreach ($modelWeek as $idDay) {
                if (!array_key_exists($idDay, $week[$key]) ) {
                    $week[$key][$idDay] = $this->Temps->newEntity();
                }
            }
        }
        return [$week, $lock];
    }

    private function getProjects($idu, $lundi, $dimanche)
    {
        $participantTable = TableRegistry::get('Participant');
        $activitiesTable = TableRegistry::get('Activities');
        $arrayProjects = array();
        $arrayRetour = array('projets'=>[], 'clients'=>[], 'profiles'=>[], 'activities'=>[]);
        $particpations = $participantTable->find('all')
            ->where(['idu =' => $idu])
            ->andWhere(['date_debut <=' => $dimanche->i18nFormat('YYYY-MM-dd 23:59:59')])
            ->andWhere(['date_fin >=' => $lundi->i18nFormat('YYYY-MM-dd 00:00:0')])
            ->contain(['Projet' => ['Client'=>['Matrice'=>['LignMat'=>['Profil']]]]])->all();
        foreach ($particpations as $participant) {
            $projet = $participant->projet;
            $arrayProjects[$projet->idc . '.' . $projet->idp] = $projet;
            $arrayRetour['projets'][$projet->idc . '.' . $projet->idp] = $projet->nom_projet;
        }
        $arrayClients = array();
        foreach ($arrayProjects as $projet) {
            $arrayClients[$projet->idc] = $projet->client;
            $arrayRetour['clients'][$projet->idc] = $projet->client->nom_client;


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

    private function getClients($projects)
    {
        $participantTable = TableRegistry::get('Participant');

        $particpations = $participantTable->findByIdu($idu)->contain(['Projet'])->all();
        $projects=array();
        foreach ($particpations as $participant) {
            $projet = $participant->projet;
            $projects[$projet->idp] = $projet->nom_projet;
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $temp = $this->Temps->newEntity();
        if ($this->request->is('post')) {
            $temp = $this->Temps->patchEntity($temp, $this->request->getData());
            if ($this->Temps->save($temp)) {
                $this->Flash->success(__('The temp has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The temp could not be saved. Please, try again.'));
        }
        $this->set(compact('temp'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Temp id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $temp = $this->Temps->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $temp = $this->Temps->patchEntity($temp, $this->request->getData());
            if ($this->Temps->save($temp)) {
                $this->Flash->success(__('The temp has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The temp could not be saved. Please, try again.'));
        }
        $this->set(compact('temp'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Temp id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $temp = $this->Temps->get($id);
        if ($this->Temps->delete($temp)) {
            $this->Flash->success(__('The temp has been deleted.'));
        } else {
            $this->Flash->error(__('The temp could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
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
