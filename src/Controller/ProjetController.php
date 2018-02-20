<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $projet = $this->paginate($this->Projet);

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
            'contain' => []
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
        $projet = $this->Projet->newEntity();
        if ($this->request->is('post')) {
            $projet = $this->Projet->patchEntity($projet, $this->request->getData());
            if ($this->Projet->save($projet)) {
                $this->Flash->success(__('The projet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The projet could not be saved. Please, try again.'));
        }
        $this->set(compact('projet'));
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
        $projet = $this->Projet->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $projet = $this->Projet->patchEntity($projet, $this->request->getData());
            if ($this->Projet->save($projet)) {
                $this->Flash->success(__('The projet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The projet could not be saved. Please, try again.'));
        }
        $this->set(compact('projet'));
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
            $this->Flash->success(__('The projet has been deleted.'));
        } else {
            $this->Flash->error(__('The projet could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
