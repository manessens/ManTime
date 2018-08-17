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
            'contain'   => ['Client', 'Matrice', 'Facturable'],
            'sortWhitelist' => [
                'Client.nom_client', 'Projet.nom_projet', 'Projet.id_fit', 'Facturable.nom_fact', 'Matrice.nom_matrice', 'Projet.prix', 'Projet.date_debut', 'Projet.date_fin'
            ],
            'order' => [
                'Client.nom_client' => 'asc'
            ]
        ];
        $this->set('projet', $this->paginate($this->Projet));
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
            'contain' => ['Client', 'Activities' => ['Activitie'], 'Participant' => ['Users'], 'Matrice', 'Facturable']
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
        $clientOption = $this->getClientOption();
        $factOption = $this->getFactOption();
        $participantsOption = $this->getUserOption();
        $activitiesOption = $this->getActivitiesOption();
        $matricesOption = $this->getmatricesOption();
        $projet = $this->Projet->newEntity();
        $myParticipants = array();
        $myActivities = array();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['date_debut'] = FrozenTime::parse($data['date_debut']);
            $data['date_fin'] = FrozenTime::parse($data['date_fin']);
            $myParticipants = $data['participant'];
            $myActivities = $data['activities'];

            $projet = $this->Projet->patchEntity($projet, $data,[
                'associated' => ['Activities', 'Participant']
            ]);
            //sauvegarde initial
            if ($this->Projet->save($projet)) {
                if ($this->updateParticipant($projet, $data['participant'])
                && $this->updateActivities($projet, $data['activities']) ){
                    //sauvegarde après mise à jour des listes
                    $this->Projet->save($projet);
                    $this->Flash->success(__('Le projet à été sauvegardé avec succés.'));

                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__("Le projet n'a pus être sauvegardé. Merci de retenter ultérieurment."));
        }
        asort($clientOption);
        asort($participantsOption);
        asort($activitiesOption);
        asort($matricesOption);
        $this->set(compact('projet'));
        $this->set(compact('clientOption'));
        $this->set(compact('factOption'));
        $this->set(compact('matricesOption'));
        $this->set('participants', $participantsOption);
        $this->set('myParticipants', $myParticipants);
        $this->set('activities', $activitiesOption);
        $this->set('myActivities', $myActivities);
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
        $factOption = $this->getFactOption();
        $participantsOption = $this->getUserOption();
        $activitiesOption = $this->getActivitiesOption();
        $matricesOption = $this->getmatricesOption();
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
                    $this->Flash->success(__('Le projet à été sauvegardé avec succés.'));
                    //retour à la liste en cas de succés
                    return $this->redirect(['action' => 'index']);
                }
            }
            $this->Flash->error(__("Le projet n'a pus être sauvegardé. Merci de retenter ultérieurment."));
        }
        asort($clientOption);
        asort($participantsOption);
        asort($activitiesOption);
        asort($matricesOption);
        $this->set(compact('projet'));
        $this->set(compact('clientOption'));
        $this->set(compact('factOption'));
        $this->set(compact('matricesOption'));
        $this->set('participants', $participantsOption);
        $this->set('myParticipants', $this->getMyParticipantsOption($projet->idp));
        $this->set('activities', $activitiesOption);
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

    public function getProjectFitnet(){
        $found = [];

        if( $this->request->is('ajax') ) {
            $this->autoRender = false; // Pas de rendu
        }

        if ($this->request->is(['get'])) {

            $id_client = $this->request->query["client"];
            if ($id_client != null) {

                // récupération des id company fitnet
                $clientTable = TableRegistry::get('Client');
                $client = $clientTable->get($id_client, [
                    'contain' => ['Agence']
                ]);
                $id_fit = $client->agence->id_fit;

                // séparation des id_agence fitnet
                $ids = explode(';', $id_fit);
                foreach($ids as $id){
                    if ($id != "") {
                        // appel de la requête
                        $result = $this->getFitnetLink("/FitnetManager/rest/projects/".$id);
                        // décode du résultat json
                        $vars = json_decode($result, true);
                        // sauvegarde des résultats trouvés
                        $found = array_merge($found, $vars);
                    }
                }
            }
        }

        $select2 = ['select' => array(), 'projects' => array()];
        //remise en forme du tableau
        foreach ($found as $value) {
            if ($value['customer'] == $client->id_fit or $client->id_fit == null) {
                $select2['select'][]=array('id'=>$value['forfaitId'], 'text'=>$value['title']);
                $select2['projects'][$value['forfaitId']]=$value;
            }
        }

        // réencodage pour renvoie au script ajax
        $json_found = json_encode($select2);
        // type de réponse : objet json
        $this->response->type('json');
        // contenue de la réponse
        $this->response->body($json_found);

        return $this->response;
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

    private function getFactOption()
    {
        $factTable = TableRegistry::get('Facturable');
        $factOption = array();
        $factOption = $factTable->find('list',['fields'=>['idf', 'nom_fact']])->toArray();
        return $factOption;
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

    private function getMatricesOption()
    {

        $matriceTable = TableRegistry::get('Matrice');
        $query = $matriceTable->find('all');
        $matrices = $query->toArray();
        $matricesOption = [];
        foreach ($matrices as $matrice) {
            $matricesOption[$matrice->idm] = $matrice->nom_matrice;
        }
        asort($matricesOption);
        return $matricesOption;
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

        try {
            $this->Projet->delete($projet);
            $this->Flash->success(__('Le projet à été supprimé avec succés'));
        } catch (\PDOException $e) {
            $this->Flash->error(__("Le projet n'a pus être supprimé. Assurez-vous qu'il ne soit pas utilisé avant de retenter."));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index', 'view', 'add', 'edit','delete','getProjectFitnet']) && $user['role'] >= \Cake\Core\Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }
}
