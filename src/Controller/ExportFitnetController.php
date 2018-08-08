<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ExportFitnet Controller
 *
 * @property \App\Model\Table\ExportFitnetTable $ExportFitnet
 *
 * @method \App\Model\Entity\ExportFitnet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExportFitnetController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $exportFitnet = $this->paginate($this->ExportFitnet);

        $this->set(compact('exportFitnet'));
    }

    /**
     * View method
     *
     * @param string|null $id Export Fitnet id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $exportFitnet = $this->ExportFitnet->get($id, [
            'contain' => []
        ]);

        $this->set('exportFitnet', $exportFitnet);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $exportFitnet = $this->ExportFitnet->newEntity();
        if ($this->request->is('post')) {
            $exportFitnet = $this->ExportFitnet->patchEntity($exportFitnet, $this->request->getData());
            if ($this->ExportFitnet->save($exportFitnet)) {
                $this->Flash->success(__('The export fitnet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The export fitnet could not be saved. Please, try again.'));
        }
        $this->set(compact('exportFitnet'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Export Fitnet id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $exportFitnet = $this->ExportFitnet->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $exportFitnet = $this->ExportFitnet->patchEntity($exportFitnet, $this->request->getData());
            if ($this->ExportFitnet->save($exportFitnet)) {
                $this->Flash->success(__('The export fitnet has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The export fitnet could not be saved. Please, try again.'));
        }
        $this->set(compact('exportFitnet'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Export Fitnet id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $exportFitnet = $this->ExportFitnet->get($id);
        if ($this->ExportFitnet->delete($exportFitnet)) {
            $this->Flash->success(__('The export fitnet has been deleted.'));
        } else {
            $this->Flash->error(__('The export fitnet could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
