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

        $usersTable = TableRegistry::get('Users');
        $idUserAuth = $this->Auth->user('idu');
        $user = $usersTable->findByIdu($idUserAuth, [
            'contain' => []
        ])->firstOrFail();

        // $lundi->i18nFormat('dd/MM');
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
        // date("W", strtotime($dimanche->i18nFormat('YYYY-MM-/dd')));

        $arrayTemps = $this->Temps->find('all')
                ->where(['idu =' => $idUserAuth])
                ->andWhere(['date >=' => $lundi->i18nFormat('YYYY-MM-dd 00:00:00')])
                ->andWhere(['date <=' => $dimanche->i18nFormat('YYYY-MM-dd 23:59:59')])
                ->all();

        $buff = array();
        foreach ($arrayTemps as $temps) {
            $buff[$temps->n_ligne][] = $temps;
        }

        $week = $this->getDaysInWeek($buff);

        if ($this->request->is(['patch', 'post', 'put'])) {
            pr($user);
            pr($this->request->getData());exit;

        }
        $arrayRetour = $projects = $clients = $profilMatrices = array();
        $arrayRetour = $this->getProjects($user->idu, $lundi, $dimanche);
        $fullNameUserAuth = $user->fullname;

        // $week = array();
        $week = [1 => 1, 2=> 1];
        $weekLine = array();
        for ($i=0; $i < 7; $i++) {
            $day = $this->Temps->newEntity();
            $day->date = $lundi;
            $weekLine[] = $day;
            // $lundi->modify('+1 days');
        }

        // $this->set(compact('temps'));
        $this->set(compact('week'));
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('current'));
        $this->set(compact('lundi'));
        $this->set(compact('dimanche'));
        $this->set(compact('fullNameUserAuth'));
        $this->set('projects', $arrayRetour['projets']);
        $this->set('clients', $arrayRetour['clients']);
        $this->set('profiles', $arrayRetour['profiles']);
        $this->set('activities', $arrayRetour['activities']);
    }

    private function getDaysInWeek($buff)
    {
        $week = array();
        $modelWeek = array('Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa', 'Di');
        foreach ($buff as $key => $arrayDays) {
            foreach ($arrayDays as $day) {
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
        return $week;
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
            $arrayProjects[$projet->idp] = $projet;
            $arrayRetour['projets'][$projet->idp] = $projet->nom_projet;
        }
        $arrayClients = array();
        foreach ($arrayProjects as $projet) {
            $arrayClients[$projet->idp . '.' . $projet->idc] = $projet->client;
            $arrayRetour['clients'][$projet->idp . '.' . $projet->idc] = $projet->client->nom_client;


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

        if (in_array($action, ['index']) ) {
            return true;
        }

        if (in_array($action, ['index-admin', 'export']) && $user['admin'] === 1 ) {
            return true;
        }

        return false;
    }
}
