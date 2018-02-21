<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Activitie Controller
 *
 * @property \App\Model\Table\ActivitieTable $Activitie
 *
 * @method \App\Model\Entity\Activitie[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ActivitieController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $activitie = $this->paginate($this->Activitie);

        $this->set(compact('activitie'));
    }

    /**
     * View method
     *
     * @param string|null $id Activitie id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $activitie = $this->Activitie->get($id, [
            'contain' => []
        ]);

        $this->set('activitie', $activitie);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $activitie = $this->Activitie->newEntity();
        if ($this->request->is('post')) {
            $activitie = $this->Activitie->patchEntity($activitie, $this->request->getData());
            if ($this->Activitie->save($activitie)) {
                $this->Flash->success(__('The activitie has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The activitie could not be saved. Please, try again.'));
        }
        $this->set(compact('activitie'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Activitie id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $activitie = $this->Activitie->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $activitie = $this->Activitie->patchEntity($activitie, $this->request->getData());
            if ($this->Activitie->save($activitie)) {
                $this->Flash->success(__('The activitie has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The activitie could not be saved. Please, try again.'));
        }
        $this->set(compact('activitie'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Activitie id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $activitie = $this->Activitie->get($id);
        if ($this->Activitie->delete($activitie)) {
            $this->Flash->success(__('The activitie has been deleted.'));
        } else {
            $this->Flash->error(__('The activitie could not be deleted. Please, try again.'));
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
