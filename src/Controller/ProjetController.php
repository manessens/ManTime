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
            'contain' => ['Client', 'Activity', 'Participant']
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
        $clientTable = TableRegistry::get('Client');
        $query = $clientTable->find('all');
        $clients = $query->toArray();
        $clientOption = [];
        foreach ($clients as $client) {
            $clientOption[$client->idc] = $client->nom_client;
        }
        $projet = $this->Projet->newEntity();
        if ($this->request->is('post')) {
            $debut = FrozenTime::parse($this->request->getData()['date_debut']);
            $fin = FrozenTime::parse($this->request->getData()['date_fin']);
            $projet = $this->Projet->patchEntity($projet, $this->request->getData(),[
                'associated' => ['Activity', 'Participant']
            ]);
            $projet->date_debut = $debut;
            $projet->date_fin = $fin;
            if ($fin > $debut) {
                if ($this->Projet->save($projet)) {
                    $this->Flash->success(__('Le projet à été sauegardé avec succées.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__("Le projet n'a pus être sauvegardé. Merci de retenter ultérieurment."));
            }else{
                $this->Flash->error(__("Le projet n'a pus être sauvegardé, date de fin inférieur à celle de début."));
            }
        }
        $this->set(compact('projet'));
        $this->set(compact('clientOption'));
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
        $clientTable = TableRegistry::get('Client');
        $query = $clientTable->find('all');
        $clients = $query->toArray();
        $clientOption = [];
        foreach ($clients as $client) {
            $clientOption[$client->idc] = $client->nom_client;
        }
        $projet = $this->Projet->get($id, [
            'contain' => ['Client', 'Activity', 'Participant']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $debut = FrozenTime::parse($this->request->getData()['date_debut']);
            $fin = FrozenTime::parse($this->request->getData()['date_fin']);
            $projet = $this->Projet->patchEntity($projet, $this->request->getData(),[
                'associated' => ['Activity', 'Participant']
            ]);
            $projet->date_debut = $debut;
            $projet->date_fin = $fin;
            if ($fin > $debut) {
                if ($this->Projet->save($projet)) {
                    $this->Flash->success(__('Le projet à été sauegardé avec succées.'));

                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__("Le projet n'a pus être sauvegardé. Merci de retenter ultérieurment."));
            }else{
                $this->Flash->error(__("Le projet n'a pus être sauvegardé, date de fin inférieur à celle de début."));
            }
        }
        $this->set(compact('projet'));
        $this->set(compact('clientOption'));
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
