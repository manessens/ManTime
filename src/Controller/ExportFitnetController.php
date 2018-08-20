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
                'ExportFitnet.id_fit','ExportFitnet.date_debut','ExportFitnet.date_fin','Client.nom_client', 'Users.prenom','ExportFitnet.etat'
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

        $filename = Configure::read('fitnet.logname_end') . $id . '.csv';
        if (!file_exists ( $filename ) ) {
            $filename = Configure::read('fitnet.logname') . $id . '.csv';
            if (!file_exists ( $filename )) {
                $this->Flash->error(__("Aucun fichier de log trouvés, veuillez contacter un administrateur."));
                return $this->redirect(['action' => 'index']);
            }
        }
        $file = fopen($filename);
        //  @TODO : traitement du fichier de log
        fclose($file);

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

        $date_debut = Time::parse($export->date_debut);
        $date_fin = Time::parse($export->date_fin);
        $data_client = $export->idc;
        $data_user =  $export->idu;

        $times = array();
        $data = array();
        $periodes = array();
        $exportableTable = TableRegistry::get('Exportable');
        $semaineDebut = (int)date('W', strtotime($date_debut->i18nFormat('dd-MM-YYYY')));
        $anneeDebut = (int)date('Y', strtotime($date_debut->i18nFormat('dd-MM-YYYY')));
        $semaineFin = (int)date('W', strtotime($date_fin->i18nFormat('dd-MM-YYYY')));
        $anneeFin =   (int)date('Y', strtotime($date_fin->i18nFormat('dd-MM-YYYY')));

        $arraNSem = array($anneeDebut => array());

        $y=$anneeDebut;
        for ($i=$semaineDebut; ($i <= $semaineFin || $y < $anneeFin) ; $i++) {
            if ($i > 52) {
                $i = 1;
                $y++;
            }
            $arraNSem[$y][] = $i;
        }
        $query = null;
        $query = $exportableTable->find('all');
        $andWhere = array();
        foreach ($arraNSem as $an => $sem) {
            if (!empty($sem)) {
                $andWhere[] = ['n_sem IN' => $sem, 'annee =' => $an];
            }
        }
        $query->where(['OR' => $andWhere]);
        $periodes = $query->toArray();

        $andWhere = array();
        $times=array();
        $queryError = false;
        if (!empty($periodes)) {
            foreach ($periodes as $periode) {
                $lundi = new Time('now');
                $lundi->setTime(00, 00, 00);
                $lundi->setISOdate($periode->annee, $periode->n_sem);
                $dimanche = clone $lundi;
                $dimanche->modify('+7 days');

                $andWhere[] = [ 'date >=' => $lundi,
                                'date <' => $dimanche,
                            ];
            }
            $query = null;
            $query = $this->Temps->find('all')->contain(['Projet'=>['Client', 'Facturable'], 'User', 'Profil'])
                ->where(['date >=' => $date_debut, 'date <=' => $date_fin, 'validat =' => 1])
                ->andwhere(['OR' => $andWhere]);
            if ( $data_client != null) {
                $ProjetTable = TableRegistry::get('Projet');
                $arrayIdProjet = $ProjetTable->find('list',['fields' =>['idc','idp']])->where(['idc =' => $data_client])->toArray();
                if (!empty($arrayIdProjet)) {
                    $query->andWhere(['idp IN' => $arrayIdProjet]);
                }else{
                    $queryError = true;
                }
            }
            if ($data_user != null ){
                $query->andWhere(['idu =' => $data_user]);
            }

            if ($queryError) {
                $times=array();
                return $times;
            }
            $times = $query->toArray();
        }
        return $times;

    }

    private function inError($export, $cause){
        // Notification d'erreur de traitement
        if ($export != null) {
            $line = ['##', ' ERREUR -- EXPORT FITNET #'.$export->id_fit.' : ', $cause];

            if ($export->etat != Configure::read('fitnet.err')) {
                $export->etat = Configure::read('fitnet.err');
            }
        }else{
            $line = ['##', ' ERREUR -- time : ', $cause];
        }
        $this->insertLog($line, true);

        return $export;

    }
    private function inProcess($export){

        $filename = Configure::read('fitnet.logname') . $export->id_fit . '.csv';
        if (file_exists ( $filename ) ) {
            unlink($filename);
        }
    	$this->file_log = fopen($filename, 'w+');

        $export->etat = Configure::read('fitnet.run');
        // Notification de lancement du traitemnt
        $line = ['>>', ' Début du traitement EXPORT FITNET pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($line);

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
            $this->error_log[] = ["--"," Nb erreur : 0 - Aucune erreur détectée"];
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

    private function insertLog( Array $line, $error = false){
        // Ecrit une nouvelle ligne dans un log d'export #$id
        if ( empty($line) ) {
            return;
        }

        $now = Time::now();
        // sécu
        if (!is_array($line)) {
            $line = [$line];
        }

        if ($line[0] != '##' || $line[0] != '<<' || $line[0] != '>>' || $line[0] != '--'){
            $line = array_merge(['--'], $line);
        }
        $line = array_merge([$now->i18nFormat('dd-MM-yy HH:mm:ss')], $line);

        if ($error) {
            $this->error_log[] = $line;
        }else{
            $this->data_log[] = $line;
        }

		fputcsv($this->file_log, $line, $this->delimiteur);

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
        }else{
            //traitement des Temps
            $count = 0;
            foreach ($times as $time) {
                if ($this->exportTime($time)) {
                    $count++;
                }else{
                    $export = $this->inError($export, 'id : #'.$id.' - Consultant : '.$time->idu.' - date : '.$time->date);
                }
            }
        }

        $export=$this->endExport($export, $count, count($times));

    }
    private function exportTime($time){
        $error = false;
        //@TODO:  recherche du assignement
        $assignement = $this->getAssignement($time);
        if ($assignement == null) {
            $this->inError(null, 'Aucun assignement trouvé pour le Temps : Consultant : '.$time->user->idu.
                                 ' projet : '.$time->projet->nom_projet.
                                 ' date : '. $time->date->i18nFormat('dd-MM-yy') );
        }
        // activityType
        // Récupération des assignement
        // employeeID
        // customerID
        // proectID
        // StartDate/EndDate



        return !$error;
    }

    private function getAssignement($time = null){
        if (empty($time)) {
            $this->inError(null, 'Null-pointer sur un traitement Temps');
            return;
        }

        $assignement = null;

        $activityType = $time->projet->facturable->id_fit;

        $this->insertLog(['--', $activityType]);

        return $assignement;
    }

    private function endExport($export, $count, $total){
        if ($count != $total) {
            $export=$this->inError($export, 'nombre de saisie échoué :'.($total-$count));
        }elseif (empty($this->error_log)) {
            $export->etat = Configure::read('fitnet.end');
        }

        $line = ['<<', ' Fin du traitement EXPORT FITNET pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($line);

        fclose($this->file_log);

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
