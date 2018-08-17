<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Form\ExportForm;
use Cake\I18n\Time;
use Cake\Core\Configure;
use App\Controller\TempsController;

/**
 * ExportFitnet Controller
 *
 * @property \App\Model\Table\ExportFitnetTable $ExportFitnet
 *
 * @method \App\Model\Entity\ExportFitnet[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class ExportFitnetController extends AppController
{

    var $file_log;
    var $data_log;
    var $error_log;
    var $delimiteur;

    public function initialize()
    {
        parent::initialize();
        $this->data_log = array();
        $this->error_log = array();
        $this->delimiteur = ';';
    }

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
                $arrayData['etat'] = Configure::read('fitnet.wait');
                $arrayData['idc'] = $arrayData['client'];
                $arrayData['idu'] = $arrayData['user'];

                $export = $this->ExportFitnet->newEntity();
                $export = $this->ExportFitnet->patchEntity($export, $arrayData);

                if ($this->ExportFitnet->save($export)) {
                    $this->Flash->info(__('Export vers fitnet programmé, vous pouvez suivre son avancement depuis la liste des exports Fitnet.'));
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

    public function view($id=null){

        if ($id == null) {
            return $this->redirect(['action' => 'index']);
        }

        $export = $this->ExportFitnet->get($id);

        $log_array = $this->readLog($id);

        $this->set(compact('export'));
        $this->set(compact('log_array'));
    }

    private function readLog($id = null){
        $log = array();
        if ($id == null) {
            return;
        }

        // @TODO : Lecture du log
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
                $this->Flash->success(__('La demande d\'export a été supprimé avec succés.'));
            } catch (\PDOException $e) {
                $this->Flash->error(__("La demande d'export n'a pus être supprimé. Assurez-vous qu'il ne soit pas utilisé avant de réessayer."));
            }
        }elseif ($export->etat == Configure::read('fitnet.err')) {
            $export->etat = Configure::read('fitnet.nerr');
            $this->ExportFitnet->save($export);
            $this->Flash->success(__('La demande d\'export en erreur à été fixé.'));
        }else{
            $this->Flash->error(__("La demande d'export n'a pus être supprimé, celle-ci est soit terminée soit en cours de traitement."));
        }

        return $this->redirect(['action' => 'index']);
    }

    private function getExportActif(){
        return $this->ExportFitnet->find('all')->where(['etat =' => Configure::read('fitnet.wait')])->toArray();
    }

    private function getTimesFromExport($export){

        $temps = new TempsController();
        $date_debut = Time::parse($export->date_debut);
        $date_fin = Time::parse($export->date_fin);
        $times = $temps->getTimes($date_debut, $date_fin, $export->idc, $export->idu );
        return $times;

    }

    private function inError($export, $cause){
        // Notification d'erreur de traitement
        $line = ['ERREUR -- EXPORT FITNET #'.$export->id_fit.' : '.$cause];
        $this->insertLog($export->id_fit, $line, true);

        if ($export->etat != Configure::read('fitnet.err')) {
            $export->etat = Configure::read('fitnet.err');
        }

        return $export;

    }
    private function inProcess($export){

        $filename = Configure::read('fitnet.logname') . $id . '.csv';
        if (file_exists ( $filename ) ) {
            unlink($filename);
        }
    	$fichier_csv = fopen($filename, 'w+');

        $export->etat = Configure::read('fitnet.run');
        // Notification de lancement du traitemnt
        $line = ['>> Début du traitement EXPORT FITNET pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($export->id_fit, $line);

        $this->ExportFitnet->save($export);
        return $export;
    }

    private function writeLog($id){
        // Ecrit une nouveau log pour l'export #$id
        $filename = Configure::read('fitnet.logname_end') . $id . '.csv';
        if (file_exists ( $filename ) ) {
            unlink($filename);
        }
    	$fichier_csv = fopen($filename, 'w+');
        if (empty($this->error_log)) {
            $this->error_log[] = "Erreur : 0 - Aucune erreur détecté";
        }
        $this->data_log = array_merge($this->error_log, $this->data_log);
    	foreach( $this->data_log as $output){
            if (!is_array($output)) {
                $output = [$output];
            }
    		fputcsv($fichier_csv, $output, $this->delimiteur);
    	}
    	fclose($fichier_csv);
    }

    private function insertLog($id, $lines = array(), $error = false){
        // Ecrit une nouvelle ligne dans un log d'export #$id
        if ( empty($line) ) {
            return;
        }

        foreach ($lines as $line) {
            $line = $line.'\n';
    		fputcsv($this->file_log, $line, $this->delimiteur);
        }

        if ($error) {
            $this->error_log[] = $lines;
        }else{
            $this->data_log[] = $lines;
        }

    }

    private function processExport($export){
        if ($export == null) {
            return;
        }

        // notif export : etat = In process
        $export = $this->inProcess($export);

        // Récupération des temps
        $times = $this->getTimesFromExport($export);
        if (empty($times)) {
            // notif export : erreur si 0 temps - FIN de traitement
            $export=$this->inError($export, 'Aucun temps trouvé sur la sélection');
            $this->ExportFitnet->save($export);
            return;
        }
        //traitement des Temps
        $count = 0;
        foreach ($times as $time) {
            if ($this->exportTime($time)) {
                $count++;
            }else{
                $export = $this->inError($export, 'id : #'.$id.' - Consultant : '.$time->idu.' - date : '.$time->date);
            }
        }

        $export=$this->endExport($export, $count, count($times));

    }
    private function exportTime($time){
        $error = false;



        return !$error;
    }

    private function endExport($export, $count, $total){
        if ($count != $total) {
            $export=$this->inError($export, 'nombre de saisie échoué :'.($total-$count));
        }

        $line = ['<< Fin du traitement EXPORT FITNET pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($export->id_fit, $line);

        fclose($this->log_file);

        $this->ExportFitnet->save($export);

    }

    public function launchExport(){
        $exports = $this->getExportActif();
        foreach ($exports as $export) {

            $this->data_log = array();
            $this->error_log = array();

            $this->processExport($export);

            $this->writeLog($export->id_fit);
        }
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

        if (in_array($action, ['export', 'index', 'add', 'delete', 'view']) && $user['role'] >= Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
