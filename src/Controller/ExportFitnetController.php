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
    public function index(){
    //     $this->paginate =[
    //         'contain'   => ['Agence'],
    //         'sortWhitelist' => [
    //             'Client.nom_client', 'Client.id_fit','Agence.nom_agence'
    //         ],
    //         'order'     => ['Client.nom_client'=>'asc']
    //     ];
    //     $this->set('client', $this->paginate($this->Client));
    //     $this->set(compact('client'));
    }

    public function exportFitnet(){

        if ($this->request->is(['post'])) {
            $arrayData = $this->request->getData();
        }
        $this->Flash->info(__('Export vers fitnet programmé, vous pouvez suivre son avancement depuis le suivie des exports.'));
        return $this->redirect(['controller'=> 'Temps' ,'action' => 'export']);
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

        if (in_array($action, ['exportFitnet']) && $user['role'] >= 50 ) {
            return true;
        }

        return false;
    }

}
