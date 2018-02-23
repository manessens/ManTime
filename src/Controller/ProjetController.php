<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\I18n\FrozenTime;

/**
 * Projet Controller
 *
 * @property \App\Model\Table\ProjetTable $Projet
 *
 * @method \App\Model\Entity\Projet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProjetController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate = [
            'contain'   => ['Client']
        ];
        $this->set('projet', $this->paginate($this->Projet));
        $this->set(compact('projet'));
    }

    /**
     * View method
     *
     * @param string|null $id Projet id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $projet = $this->Projet->get($id, [
            'contain' => ['Client', 'Activities' => ['Activitie'], 'Participant' => ['Users']]
        ]);

        $this->set('projet', $projet);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $clientOption = getClientOption();
        $projet = $this->Projet->newEntity();
        if ($this->request->is('post')) {
            $debut = FrozenTime::parse($this->request->getData()['date_debut']);
            $fin = FrozenTime::parse($this->request->getData()['date_fin']);
            $projet = $this->Projet->patchEntity($projet, $this->request->getData(),[
                'associated' => ['Activities', 'Participant']
            ]);
            $projet->date_debut = $debut;
            $projet->date_fin = $fin;
            if ($fin > $debut) {
                if ($this->Projet->save($projet)) {
                    $this->Flash->success(__('Le projet à été sauegardé avec succés.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__("Le projet n'a pus être sauvegardé. Merci de retenter ultérieurment."));
            }else{
                $this->Flash->error(__("Le projet n'a pus être sauvegardé, date de fin inférieur à celle de début."));
            }
        }
        $this->set(compact('projet'));
        $this->set(compact('clientOption'));
        $this->set('particpants', $this->getUserOption());
        $this->set('myParticpants', $this->getMyParticipantsOption($projet->idp));
        $this->set('activities', $this->getActivitiesOption());
        $this->set('myActivities', $this->getMyActivitiesOption($projet->idp));
    }

    /**
     * Edit method
     *
     * @param string|null $id Projet id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $clientOption = $this->getClientOption();
        $projet = $this->Projet->get($id, [
            'contain' => ['Activities', 'Participant' ]
        ]);
        // Si envoie du formulaire : update table
        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            $data['date_debut'] = FrozenTime::parse($data['date_debut']);
            $data['date_fin'] = FrozenTime::parse($data['date_fin']);

            $projet = $this->Projet->patchEntity($projet, $data,[
                'associated' => ['Activities', 'Participant']
            ]);
            // mise à jour des relation hasMany
            if ($this->updateParticipant($projet, $data['participant'])
            && $this->updateActivities($projet, $data['activities']) ){
                if ( $this->Projet->save($projet) ) {
                    $this->Flash->success(__('Le projet à été sauegardé avec succés.'));
                    //retour à la liste en cas de succés
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__("Le projet n'a pus être sauvegardé. Merci de retenter ultérieurment."));
        }
        $this->set(compact('projet'));
        $this->set(compact('clientOption'));
        $this->set('particpants', $this->getUserOption());
        $this->set('myParticpants', $this->getMyParticipantsOption($projet->idp));
        $this->set('activities', $this->getActivitiesOption());
        $this->set('myActivities', $this->getMyActivitiesOption($projet->idp));
    }

    private function updateActivities( $projet, $activities = array())
    {
        $activitiesTable = TableRegistry::get('Activities');
        $activitiesObject = array();
        if ( !empty($activities) ) {
            foreach ($activities as $value) {
                $activitiesObject[] = $activitiesTable->newEntity(['idp' => $projet->idp, 'ida' => $value]);
            }
            //Prepare query for deletion
            $query = $activitiesTable->find('all')->where(['idp =' => $projet->idp, 'ida NOT IN' => $activities ]);
        }else{
            //Prepare query for deletion in case of empty array
            $query = $activitiesTable->find('all')->where(['idp =' => $projet->idp ]);
        }
        // Update liste of activities to create associated entity in tab
        $projet->activities = $activitiesObject;
        //DELETION
        $listDeletion = $query->toArray();
        foreach ($listDeletion as  $entity) {
            $result = $activitiesTable->delete($entity);
            if ( !$result ) {
                $this->Flash->error(__("Le projet n'a pus être sauvegardé. Erreur à l'enregistrement des activités."));
                return false;
            };
        }
        return true;
    }
    private function updateParticipant( $projet, $participant = array())
    {
        $participantTable = TableRegistry::get('Participant');
        $participants = array();
        if ( !empty($participant) ) {
            foreach ($participant as $value) {
                $participants[] = $participantTable->newEntity(['idp' => $projet->idp, 'idu' => $value]);
            }
            //Prepare query for deletion
            $query = $participantTable->find('all')->where(['idp =' => $projet->idp, 'idu NOT IN' => $participant ]);
        }else{
            //Prepare query for deletion in case of empty array
            $query = $participantTable->find('all')->where(['idp =' => $projet->idp ]);
        }
        // Update liste of participant to create associated entity in tab
        $projet->participant = $participants;

        //DELETION
        $listDeletion = $query->toArray();
        foreach ($listDeletion as  $entity) {
            $result = $participantTable->delete($entity);
            if ( !$result ) {
                $this->Flash->error(__("Le projet n'a pus être sauvegardé. Erreur à l'enregistrement des participants."));
                return false;
            };
        }
        return true;
    }

    private function getClientOption()
    {
        $clientTable = TableRegistry::get('Client');
        $query = $clientTable->find('all');
        $clients = $query->toArray();
        $clientOption = [];
        foreach ($clients as $client) {
            $clientOption[$client->idc] = $client->nom_client;
        }
        return $clientOption;
    }

    private function getUserOption()
    {
        $userTable = TableRegistry::get('Users');
        $query = $userTable->find('all')->where(['Users.actif =' => 1]);
        $users = $query->toArray();
        $userOption = [];
        foreach ($users as $user) {
            $userOption[$user->idu] = $user->fullname;
        }
        asort($userOption);
        return $userOption;
    }

    private function getActivitiesOption()
    {
        $activitieTable = TableRegistry::get('Activitie');
        $query = $activitieTable->find('all');
        $activities = $query->toArray();
        $activitieOption = [];
        foreach ($activities as $activitie) {
            $activitieOption[$activitie->ida] = $activitie->nom_activit;
        }
        asort($activitieOption);
        return $activitieOption;
    }

    private function getMyParticipantsOption($idp = null)
    {
        $participantTable = TableRegistry::get('Participant');
        $query = $participantTable->findByIdp($idp);
        $participants = $query->toArray();
        $participantOption = array();
        foreach ($participants as $participant) {
            if ($participant->idp === $idp) {
                $participantOption[] = $participant->idu;
            }
        }
        return $participantOption;
    }

    private function getMyActivitiesOption($idp = null)
    {
        $activitieTable = TableRegistry::get('Activities');
        $query = $activitieTable->findByIdp($idp);
        $activities = $query->toArray();
        $activitiesOption = array();
        foreach ($activities as $activitie) {
            if ($activitie->idp === $idp) {
                $activitiesOption[] = $activitie->ida;
            }
        }
        return $activitiesOption;
    }

    /**
     * Delete method
     *
     * @param string|null $id Projet id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $projet = $this->Projet->get($id);
        //DELETION ACTIVTIES / PARTICIPANT
        $activitiesTable = TableRegistry::get('Activities');
        $query = $activitiesTable->find('all')->where(['idp =' => $projet->idp ]);
        $listDeletionA = $query->toArray();
        $participantTable = TableRegistry::get('Participant');
        $query = $participantTable->find('all')->where(['idp =' => $projet->idp ]);
        $listDeletionP = $query->toArray();
        foreach ($listDeletionA as  $entity) {
            if ( !$activitiesTable->delete($entity) ) {
                $this->Flash->error(__("Le projet n'a pus être supprimé. Erreur à la désaffectation des activités."));
                return $this->redirect(['action' => 'index']);
            };
        }
        foreach ($listDeletionP as  $entity) {
            if ( !$participantTable->delete($entity) ) {
                $this->Flash->error(__("Le projet n'a pus être supprimé. Erreur à la désaffectation des participants."));
                return $this->redirect(['action' => 'index']);
            };
        }
        if ($this->Projet->delete($projet)) {
            $this->Flash->success(__('Le projet à été supprimé avec succés'));
        } else {
            $this->Flash->error(__("Le projet n'a pus être supprimé. Merci de retenter ultérieurment."));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index', 'view', 'add', 'edit','delete']) && $user['admin'] === 1 ) {
            return true;
        }

        return false;
    }
}
