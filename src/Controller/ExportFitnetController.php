<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Form\ExportForm;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * ExportFitnet Controller
 *
 * @property \App\Model\Table\ExportFitnetTable $ExportFitnet
 *
 * @method \App\Model\Entity\ExportFitnet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExportFitnetController extends AppController
{
    public function index(){
        $this->paginate =[
            'contain'   => ['Client', 'Users'],
            'sortWhitelist' => [
                'ExportFitnet.date_debut','ExportFitnet.date_fin','Client.nom_client', 'Users.prenom','ExportFitnet.etat'
            ],
            'order'     => ['ExportFitnet.etat'=>'asc']
        ];
        $this->set('exports', $this->paginate($this->ExportFitnet));
        $this->set(compact('exports'));
    }

    public function add(){
        return $this->redirect(['controller'=> 'Temps' ,'action' => 'export']);
    }

    public function export(){

        $form = new ExportForm();
        $clientTable = TableRegistry::get('Client');
        $arrayClient = $clientTable->find('all')->contain(['Agence'])->toArray();
        $clients = array();
        $agenceClient = array();
        foreach ($arrayClient as $client) {
            $clients[$client->idc] = ucfirst($client->nom_client);
        }
        $userTable = TableRegistry::get('Users');
        $arrayUser = $userTable->find('all')->contain(['Origine'])->toArray();
        $users = array();
        foreach ($arrayUser as $user) {
            $users[$user->idu] = $user->fullname;
        }
        if ($this->request->is(['post'])) {
            $arrayData = $this->request->getData();

            $isValid = $form->validate($arrayData);
            if ($isValid){
                $arrayData['date_debut'] = Time::parse($arrayData['date_debut']);
                $arrayData['date_fin'] = Time::parse($arrayData['date_fin']);
                $arrayData['etat'] = 'En attente';

                $export = $this->ExportFitnet->newEntity();
                $export = $this->ExportFitnet->patchEntity($export, $arrayData);
                $export->idc = $arrayData['idc'];
                $export->idu = $arrayData['idu'];
                pr($export);exit;
                if ($this->ExportFitnet->save($export)) {
                    $this->Flash->info(__('Export vers fitnet programmé, vous pouvez suivre son avancement depuis le suivie des exports.'));
                }

            }else{
                $this->Flash->error(__('Une erreur est survenu. Merci de vérifier la saisie ou de retenter ultérieurement.'));
            }

        }

        $this->viewBuilder()->template('/Temps/export');
        $this->set('export',$form);
        $this->set('controller', false);
        $this->set(compact('clients'));
        $this->set(compact('users'));
        // $this->render('/Temps/export');
        // return $this->redirect(['controller'=> 'Temps' ,'action' => 'export']);
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
        $export = $this->ExportFitnet->get($id);
        if ($export->etat == Configure::read('fitnet.wait') ) {
            try {
                $this->ExportFitnet->delete($export);
                $this->Flash->success(__('Le client a été supprimé avec succés.'));
            } catch (\PDOException $e) {
                $this->Flash->error(__("La demande d'export n'a pus être supprimé. Assurez-vous qu'il ne soit pas utilisé avant de réessayer."));
            }
        }else{
            $this->Flash->error(__("La demande d'export n'a pus être supprimé, celle-ci est soit terminée soit en cours de traitement."));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function getProjectFitnetShell($id = null){
        $found = [];

        $id_client = $id;
        if ($id_client != null) {

            // récupération des id company fitnet
            $clientTable = TableRegistry::get('Client');
            $client = $clientTable->get($id_client, [
                'contain' => ['Agence']
            ]);
            $id_fit = $client->agence->id_fit;

            // séparation des id_agence fitnet
            $ids = explode(';', $id_fit);
            foreach($ids as $id){
                if ($id != "") {
                    // appel de la requête
                    $result = $this->getFitnetLink("/FitnetManager/rest/projects/".$id);
                    // décode du résultat json
                    $vars = json_decode($result, true);
                    // sauvegarde des résultats trouvés
                    $found = array_merge($found, $vars);
                }
            }
        }

        $select2 = ['select' => array(), 'projects' => array()];
        //remise en forme du tableau
        foreach ($found as $value) {
            if ($value['customer'] == $client->id_fit or $client->id_fit == null) {
                $select2['select'][]=array('id'=>$value['forfaitId'], 'text'=>$value['title']);
                $select2['projects'][$value['forfaitId']]=$value;
            }
        }

        // réencodage pour renvoie au script ajax
        $json_found = json_encode($select2);
        // type de réponse : objet json
        $this->response->type('json');
        // contenue de la réponse
        $this->response->body($json_found);

        return $this->response;
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['export', 'index', 'add', 'delete']) && $user['role'] >= 50 ) {
            return true;
        }

        return false;
    }

}
