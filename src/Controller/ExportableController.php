<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Exportable Controller
 *
 * @property \App\Model\Table\ExportableTable $Exportable
 *
 * @method \App\Model\Entity\Exportable[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExportableController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $exportable = $this->paginate($this->Exportable);

        $this->set(compact('exportable'));
    }

    /**
     * View method
     *
     * @param string|null $id Exportable id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exportable = $this->Exportable->get($id, [
            'contain' => []
        ]);

        $this->set('exportable', $exportable);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $exportable = $this->Exportable->newEntity();
        if ($this->request->is('post')) {
            $exportable = $this->Exportable->patchEntity($exportable, $this->request->getData());
            if ($this->Exportable->save($exportable)) {
                $this->Flash->success(__('The exportable has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exportable could not be saved. Please, try again.'));
        }
        $this->set(compact('exportable'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Exportable id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exportable = $this->Exportable->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exportable = $this->Exportable->patchEntity($exportable, $this->request->getData());
            if ($this->Exportable->save($exportable)) {
                $this->Flash->success(__('The exportable has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The exportable could not be saved. Please, try again.'));
        }
        $this->set(compact('exportable'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Exportable id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exportable = $this->Exportable->get($id);
        if ($this->Exportable->delete($exportable)) {
            $this->Flash->success(__('The exportable has been deleted.'));
        } else {
            $this->Flash->error(__('The exportable could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
