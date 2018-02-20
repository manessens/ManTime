<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

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
        $ProfilTable = TableRegistry::get('Profil');

        $matrice = $this->Matrice->newEntity();
        if ($this->request->is('post')) {
            $matrice = $this->Matrice->patchEntity($matrice, $this->request->getData(),[
                'associated' => ['LignMat']
            ]);
            $matrice->lign_mat[0]->id_profil = 1;
            $matrice->lign_mat[1]->id_profil = 2;
            $matrice->lign_mat[2]->id_profil = 3;
            $matrice->lign_mat[3]->id_profil = 4;
            if ($this->Matrice->save($matrice, ['associated' => ['LignMat'])) {
                $this->Flash->success(__('La matrice a été créée.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("La matrice n'a pus être créée. Merci de retenter ultérieurement."));
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
            $matrice = $this->Matrice->patchEntity($matrice, $this->request->getData(),[
                'associated' => ['LignMat']
            ]);
            if ($this->Matrice->save($matrice)) {
                $this->Flash->success(__('Matrice sauvegardé avec succés.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__("La matrice n'a pus être sauvegardé. merci de réessayer ultérieurement."));
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
