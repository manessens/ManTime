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
        $ligne1 = $this->Matrice->LignMat->newEntity();
        $ligne1->id_profil = 1;
        $ligne1->profil = $ProfilTable->findByIdProfil(1)->firstOrFail();
        $ligne2 = $this->Matrice->LignMat->newEntity();
        $ligne2->id_profil = 2;
        $ligne2->profil = $ProfilTable->findByIdProfil(2)->firstOrFail();
        $ligne3 = $this->Matrice->LignMat->newEntity();
        $ligne3->id_profil = 3;
        $ligne3->profil = $ProfilTable->findByIdProfil(3)->firstOrFail();
        $ligne4 = $this->Matrice->LignMat->newEntity();
        $ligne4->id_profil = 4;
        $ligne4->profil = $ProfilTable->findByIdProfil(4)->firstOrFail();
        $matrice->LignMat = [$ligne1, $ligne2 ,$ligne3 ,$ligne4];
        // pr($matrice);exit;
        if ($this->request->is('post')) {
            $matrice = $this->Matrice->patchEntity($matrice, $this->request->getData(),[
                'associated' => ['LignMat']
            ]);
            pr($matrice);exit;
            if ($this->Matrice->save($matrice)) {
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
