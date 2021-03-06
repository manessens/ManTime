<?php
namespace App\Controller;

use App\Controller\AppController;


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
                'Client.nom_client', 'Client.id_fit','Agence.nom_agence'
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
        $this->loadModel('Agence');
        $agenceOption = $this->Agence->find('list',['fields' =>['id_agence','nom_agence']])->toArray();
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
        if (is_numeric($id)) {
            $client = $this->Client->get($id);
        }
        $agenceOption = array();
        $this->loadModel('Agence');
        $agenceOption = $this->Agence->find('list',['fields' =>['id_agence','nom_agence']])->toArray();
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

    public function getCustomerVsa(){
        $found = [];

        if( $this->request->is('ajax') ) {
            $this->autoRender = false; // Pas de rendu
        }

        $select2 = array();
        if ($this->request->is(['get'])) {
            // appel de la requête
            $result = $this->getVsaLink("v1/customers");
            // décode du résultat json
            $vars = json_decode($result, true);

            if (is_array($vars)) {
                if (!array_key_exists('error', $vars)) {
                    // sauvegarde des résultats trouvés
                    $found = array_merge($found, $vars);
                }else{
                    // on notifie l'utilisateur qu'une erreur est survenu
                    $select2[]=array('id'=>'err', 'text'=>$vars['message']);
                }
            }
        }

        //remise en forme du tableau
        if (!empty($found)) {
            foreach ($found as $value) {
                $select2[]=array('id'=>$value['code'], 'text'=>$value['name']);
            }
        }else{
            // on notifie l'utilisateur qu'une erreur est survenu
            $select2[]=array('id'=>'err', 'text'=>'Erreur Lors de la récupérration de la liste des clients VSA');
        }

        // réencodage pour renvoie au script ajax
        $json_found = json_encode($select2);
        // $json_found = json_encode($result);
        // type de réponse : objet json
        $this->response->type('json');
        // contenue de la réponse
        $this->response->body($json_found);

        return $this->response;
    }


    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index', 'view', 'add', 'edit', 'delete', 'getCustomerVsa']) && $user['role'] >= \Cake\Core\Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }
}
