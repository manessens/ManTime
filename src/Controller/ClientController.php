<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Client Controller
 *
 * @property \App\Model\Table\ClientTable $Client
 *
 * @method \App\Model\Entity\Client[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ClientController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $this->paginate =[
            'contain'   => ['Agence'],
            'sortWhitelist' => [
                'Client.nom_client', 'Agence.nom_agence'
            ],
            'order'     => ['Client.nom_client'=>'asc']
        ];
        $this->set('client', $this->paginate($this->Client));
        $this->set(compact('client'));
    }

    /**
     * View method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $client = $this->Client->get($id, [
            'contain' => ['Agence']
        ]);

        $this->set('client', $client);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $client = $this->Client->newEntity();
        $agenceOption = array();
        $agenceTable = TableRegistry::get('Agence');
        $agenceOption = $agenceTable->find('list',['fields' =>['id_agence','nom_agence']])->toArray();
        if ($this->request->is('post')) {
            $client = $this->Client->patchEntity($client, $this->request->getData());
            if ($this->Client->save($client)) {
                $this->Flash->success(__('Le client a bien été sauvegardé.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("Le client n'a pus être sauvegardé. Merci de réessayer ultérieurement."));
        }
        $this->set(compact('client'));
        $this->set(compact('agenceOption'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $client = $this->Client->get($id);
        $agenceOption = array();
        $agenceTable = TableRegistry::get('Agence');
        $agenceOption = $agenceTable->find('list',['fields' =>['id_agence','nom_agence']])->toArray();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $client = $this->Client->patchEntity($client, $this->request->getData());
            if ($this->Client->save($client)) {
                $this->Flash->success(__('Le client a bien été sauvegardé.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("Le client n'a pus être sauvegardé. Merci de réessayer ultérieurement."));
        }
        $this->set(compact('client'));
        $this->set(compact('agenceOption'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Client id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $client = $this->Client->get($id);
        try {
            $this->Client->delete($client);
            $this->Flash->success(__('Le client a été supprimé avec succés.'));
        } catch (\PDOException $e) {
            $this->Flash->error(__("Le client n'a pus être supprimé. Assurez-vous qu'il ne soit pas utilisé avant de réessayer."));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if ($user['prem_connect'] === 1) {
            return false;
        }

        // if (in_array($action, ['index', 'view', 'add', 'edit','delete']) && $user['role'] >= 50 ) {
        if (in_array($action, ['index', 'view', 'add', 'edit', 'delete']) && $user['role'] >= 50 ) {
            return true;
        }

        return false;
    }
}
