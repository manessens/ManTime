<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Matrice Controller
 *
 * @property \App\Model\Table\MatriceTable $Matrice
 *
 * @method \App\Model\Entity\Matrice[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MatriceController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $matrice = $this->paginate($this->Matrice);

        $this->set(compact('matrice'));
    }

    /**
     * View method
     *
     * @param string|null $id Matrice id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $matrice = $this->Matrice->get($id, [
            'contain' => ['LignMat' => ['Profil'] ]
        ]);

        $this->set('matrice', $matrice);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $matrice = $this->Matrice->newEntity();
        if ($this->request->is('post')) {
            $matrice = $this->Matrice->patchEntity($matrice, $this->request->getData());
            if ($this->Matrice->save($matrice)) {
                $this->Flash->success(__('The matrice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The matrice could not be saved. Please, try again.'));
        }
        $this->set(compact('matrice'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Matrice id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $matrice = $this->Matrice->get($id, [
            'contain' => ['LignMat' => ['Profil'] ]
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {

            $associated = ['LignMat', 'LignMat.profil'];
            $matrice = $matrice->get($id, ['contain' => $associated]);
            $matrices->patchEntity($matrice, $this->request->getData(), [
                'associated' => $associated
            ]);
            // $matrice = $this->Matrice->patchEntity($matrice, $this->request->getData(),[
            //     'associated' => ['LignMat']
            // ]);
                pr($matrice);exit;
            if ($this->Matrice->save($matrice)) {
                $this->Flash->success(__('The matrice has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The matrice could not be saved. Please, try again.'));
        }
        $this->set(compact('matrice'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Matrice id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $matrice = $this->Matrice->get($id);
        if ($this->Matrice->delete($matrice)) {
            $this->Flash->success(__('The matrice has been deleted.'));
        } else {
            $this->Flash->error(__('The matrice could not be deleted. Please, try again.'));
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
