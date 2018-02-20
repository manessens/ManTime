<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Profil Controller
 *
 * @property \App\Model\Table\ProfilTable $Profil
 *
 * @method \App\Model\Entity\Profil[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ProfilController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $profil = $this->paginate($this->Profil);

        $this->set(compact('profil'));
    }

    /**
     * View method
     *
     * @param string|null $id Profil id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $profil = $this->Profil->get($id, [
            'contain' => ['LignMat']
        ]);

        $this->set('profil', $profil);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $profil = $this->Profil->newEntity();
        if ($this->request->is('post')) {
            $profil = $this->Profil->patchEntity($profil, $this->request->getData());
            if ($this->Profil->save($profil)) {
                $this->Flash->success(__('The profil has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The profil could not be saved. Please, try again.'));
        }
        $this->set(compact('profil'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Profil id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $profil = $this->Profil->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $profil = $this->Profil->patchEntity($profil, $this->request->getData());
            if ($this->Profil->save($profil)) {
                $this->Flash->success(__('The profil has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The profil could not be saved. Please, try again.'));
        }
        $this->set(compact('profil'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Profil id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $profil = $this->Profil->get($id);
        if ($this->Profil->delete($profil)) {
            $this->Flash->success(__('The profil has been deleted.'));
        } else {
            $this->Flash->error(__('The profil could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
