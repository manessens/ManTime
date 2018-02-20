<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * LignMat Controller
 *
 * @property \App\Model\Table\LignMatTable $LignMat
 *
 * @method \App\Model\Entity\LignMat[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class LignMatController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $lignMat = $this->paginate($this->LignMat);

        $this->set(compact('lignMat'));
    }

    /**
     * View method
     *
     * @param string|null $id Lign Mat id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $lignMat = $this->LignMat->get($id, [
            'contain' => ['Profil']
        ]);

        $this->set('lignMat', $lignMat);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $lignMat = $this->LignMat->newEntity();
        if ($this->request->is('post')) {
            $lignMat = $this->LignMat->patchEntity($lignMat, $this->request->getData());
            if ($this->LignMat->save($lignMat)) {
                $this->Flash->success(__('The lign mat has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lign mat could not be saved. Please, try again.'));
        }
        $this->set(compact('lignMat'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Lign Mat id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $lignMat = $this->LignMat->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $lignMat = $this->LignMat->patchEntity($lignMat, $this->request->getData());
            if ($this->LignMat->save($lignMat)) {
                $this->Flash->success(__('The lign mat has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The lign mat could not be saved. Please, try again.'));
        }
        $this->set(compact('lignMat'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Lign Mat id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $lignMat = $this->LignMat->get($id);
        if ($this->LignMat->delete($lignMat)) {
            $this->Flash->success(__('The lign mat has been deleted.'));
        } else {
            $this->Flash->error(__('The lign mat could not be deleted. Please, try again.'));
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
