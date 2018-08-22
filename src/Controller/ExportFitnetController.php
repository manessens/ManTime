<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use App\Form\ExportForm;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Http\Client;

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
            'order'     => ['ExportFitnet.etat'=>'asc', 'ExportFitnet.id_fit'=>'desc']
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

        $line=array();
        $filename = Configure::read('fitnet.logname_end') . $id . '.csv';
        $absFileName = Configure::read('fitnet.abs_path').Configure::read('fitnet.logdir_end').DS.$filename;
        if (!file_exists($absFileName)) {
            $filename = Configure::read('fitnet.logname') . $id . '.csv';
            $absFileName = Configure::read('fitnet.abs_path').Configure::read('fitnet.logdir').DS.$filename;
            if (!file_exists($absFileName)) {
                $this->Flash->error(__("Aucun fichier log trouvé, veuillez contacter un administrateur."));
                return $this->redirect(['action' => 'index']);
            }
        }

        $lines = file($absFileName, FILE_SKIP_EMPTY_LINES);

        $log_array = $this->readLog($lines);

        if ($this->request->is(['post'])) {
            $response = $this->response->withFile(
                $absFileName,
                ['download' => true]
            );
            return $response;
        }

        $export = $this->ExportFitnet->get($id);

        $this->set(compact('export'));
        $this->set(compact('log_array'));
    }

    private function readLog($contents = array()){
        $log = array();
        $log['error'] = array();
        $log['info'] = array();
        if (empty($contents)) {
            return;
        }

        foreach($contents as $n => $line){
            $arrayLine = explode(';', $line);
            switch ($arrayLine[1]) {
                case '##':
                    unset($arrayLine[1]);
                    $log['error'][] = $arrayLine;
                    break;
                case '>>':
                    unset($arrayLine[1]);
                    $log['info']['start'] = $arrayLine;
                    break;
                case '<<':
                    unset($arrayLine[1]);
                    $log['info']['end'] = $arrayLine;
                    break;
                case '--':
                    unset($arrayLine[1]);
                    $log['info'][] = $arrayLine;
                    break;

                default:
                    $log['info'][] = $arrayLine;
                    break;
            }
        }
        return $log;
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
            $tempsTable = TableRegistry::get('Temps');
            $query = $tempsTable->find('all')->contain(['Projet'=>['Client'=>'Agence', 'Facturable'], 'Users'=>['Origine'], 'Profil'])
                ->where([
                    'date >=' => $date_debut, 'date <=' => $date_fin,
                    'validat =' => 1,
                    'Projet.id_fit  IS NOT' => null,
                    'Client.id_fit  IS NOT' => null,
                    'Users.id_fit IS NOT' => null
                 ] )
                ->andwhere(['OR' => $andWhere]);
                // debug($query);
            if ( $data_client != null) {
                $ProjetTable = TableRegistry::get('Projet');
                $arrayIdProjet = $ProjetTable->find('list',['fields' =>['idc','idp']])->where(['idc =' => $data_client])->toArray();
                if (!empty($arrayIdProjet)) {
                    $query->andWhere(['Projet.idp IN' => $arrayIdProjet]);
                }else{
                    $queryError = true;
                }
            }
            if ($data_user != null ){
                $query->andWhere(['Temps.idu =' => $data_user]);
            }

            if ($queryError) {
                $times=array();
                return $times;
            }
            $times = $query->toArray();
        }
        return $times;

    }

    private function inError($export, $cause, $code = null){
        // Notification d'erreur de traitement
        if ($export != null) {
            $line = ['##', ' ERREUR -- EXPORT FITNET #'.$export->id_fit.' : ', $cause];
        }elseif ($code != null) {
            $line = ['##', ' ERREUR -- Fitnet : ', $cause];
        }else{
            $line = ['##', ' ERREUR -- time : ', $cause];
        }
        $this->insertLog($line, true);

        return $export;

    }
    private function inProcess($export){

        $filename = Configure::read('fitnet.logname') . $export->id_fit . '.csv';

        $folder = new Folder(Configure::read('fitnet.abs_path').Configure::read('fitnet.logdir'));
        $this->file_log = new File($folder->pwd() . DS . $filename);
        $this->file_log->delete();

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

        $folder = new Folder(Configure::read('fitnet.abs_path').Configure::read('fitnet.logdir_end'));
        $file = new File($folder->pwd() . DS . $filename);
        $file->delete();

        if (empty($this->error_log)) {
            $this->error_log[] = ["--"," Nb erreur : 0 - Aucune erreur détectée"];
        }

        $this->data_log = array_merge($this->error_log, $this->data_log);
    	foreach( $this->data_log as $output){
            if (!is_array($output)) {
                $output = [$output];
            }
            $file->append(implode($this->delimiteur, $output)."\n");
    	}
        $file->close();
    }

    private function insertLog( Array $line, $error = false, $first = false){
        // Ecrit une nouvelle ligne dans un log d'export #$id
        if ( empty($line) ) {
            return;
        }

        $now = Time::now();
        // sécu
        if (!is_array($line)) {
            $line = [$line];
        }

        $line = array_merge([$now->i18nFormat('dd-MM-yy HH:mm:ss')], $line);

        if ($error) {
            $this->error_log = $this->appendArray($this->error_log, $line, $first);
        }else{
            $this->data_log = $this->appendArray($this->data_log, $line, $first);
        }

        $this->file_log->append(implode($this->delimiteur, $line)."\n");

    }
    private function appendArray($array = array(), $line, $first){
        if ($first) {
            array_unshift($array, $line);
        }else{
            $array[] = $line;
        }
        return $array;
    }

    private function processExport($export){
        if ($export == null) {
            return;
        }

        // notif export : etat = In process
        $export = $this->inProcess($export);

        // Récupération des temps
        $times = $this->getTimesFromExport($export);
        $count = 0;
        if (empty($times)) {
            // notif export : erreur si 0 temps - FIN de traitement
            $export=$this->inError($export, 'Aucun temps trouvé sur la sélection');
            $this->ExportFitnet->save($export);
        }else{
            //traitement des Temps
            foreach ($times as $time) {
                if ($this->exportTime($time)) {
                    $count++;
                }else{
                    $export = $this->inError($export, '#'.$time->idt.' - Consultant : #'.$time->idu.' - date : '.$time->date);
                }
            }
        }

        $export=$this->endProcess($export, $count, count($times));

    }
    private function exportTime($time){
        $noError = true;

        if ($time->projet->id_fit == null) {
            $this->insertLog(['--','Le projet '.$time->projet->nom_projet."n'est pas lié à une affaire fitnet : pas d'export"]);
            return $noError;
        }

        // Récupération des assignement
        $assignementID = $this->getAssignement($time);
        if ($assignementID == null) {
            $this->inError(null, 'Aucun assignement trouvé pour le Temps : Consultant : '.$time->user->fullname.
                        ' |Projet : '.$time->projet->nom_projet.' |Date : '. $time->date->i18nFormat('dd-MM-yy') );
            $noError = false;
            return $noError;
        }

        // activityType
        $activityType = $time->projet->facturable->id_fit;
        if ($activityType == null) {
            $this->inError(null, 'ID_FIT de la table Facturable est éronné pour la ligne #'. $time->projet->facturable->idf );
            $noError = false;
        }

        // total temps travaillé
        $amount = $time->time;

        // Date
        $assignementDate = $time->date->i18nFormat('dd/MM/yyyy');

        // employeeID
        $employeeID = $time->user->id_fit;
        if ($employeeID == null) {
            // @TODO: $this->inError();
            $noError = false;
        }

        // companyID
        $companyID = $time->projet->client->agence->id_fit;
        if ($companyID == null) {
            $this->inError(null, 'ID_FIT de la table Agence est éronné pour la ligne #'. $time->projet->client->agence->id_agence );
            $noError = false;
        }

        if (!$noError) {
            return $noError;
        }

        $timesheet = [
            "activity" => "",
            "activityID" => 0,
            "activityType" => $activityType,
            "amount" => $amount,
            "assignmentDate" => $assignementDate,
            "assignmentID" => $assignementID,
            "company" => "",
            "companyID" => $companyID,
            "employee" => "",
            "employeeID" => $employeeID,
            "remark" => "",
            "timesheetAssignmentID" => 0,
            "typeOfService" => "",
            "typeOfServiceID" => Configure::read('fitnet.profil.'.$companyID.$time->id_profil)
        ];

        $timesheetJS = json_encode($timesheet);

        $url = '/FitnetManager/rest/timesheet';
        $result = $this->setFitnetLink($url, $timesheetJS);

        return $result;
    }

    private function getAssignement($time = null){
        if (empty($time)) {
            $this->inError(null, 'Null-pointer sur un traitement Temps');
            return;
        }

        $assignementIdName = [1 => 'assignmentOnContractID', 2 => 'assignmentOffContractID', 3 => 'assignmentTrainingID'];
        $assignementFind = null;
        $assignementJsonTable = array();

        $month = $time->date->i18nFormat('MM');
        $year = $time->date->i18nFormat('YYYY');

        $activityType = $time->projet->facturable->id_fit;

        switch ($activityType) {
            case 1:
                $assignementJsonTable = $this->getFitnetLink("/FitnetManager/rest/assignments/onContract/".$time->user->origine->id_fit.'/'.$month.'/'.$year);
                break;

            default:
                $this->inError(null, 'activityType éroné' );
                break;
        }
        $assignementTable = json_decode($assignementJsonTable, true);
        if ( empty($assignementTable) ) {
            return;
        }

        foreach ($assignementTable as $assignement) {
            $date_debut = new Time(str_replace('/', '-', $assignement['assignmentStartDate']));
            $date_fin = new Time(str_replace('/', '-', $assignement['assignmentEndDate']));

            if ($assignement['employeeID'] == $time->user->id_fit
            && $assignement['customerID'] == $time->client->id_fit
            && $assignement['contractID'] == $time->projet->id_fit
            && $date_debut <= $time->date && $date_fin >= $time->date ) {
                return $assignement[$assignementIdName[$activityType]];
            }
        }
        return;

    }

    private function endProcess($export, $count, $total){
        if ($count != $total) {
            $cause = 'nombre de saisie échoué : '.($total-$count);
            $line = ['##', ' ERREUR -- EXPORT FITNET #'.$export->id_fit, $cause];
            $this->insertLog($line,true,true);
        }
        if (empty($this->error_log)) {
            $export->etat = Configure::read('fitnet.end');
            $this->insertLog(['--', ' Total de temps traité : '.$total]);
            $this->insertLog(['--', ' Total de temps exporté avec succés : '.$count]);
        }else{
            $export->etat = Configure::read('fitnet.err');
        }

        $line = ['<<', ' Fin du traitement EXPORT FITNET pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($line);


        try {
            $this->ExportFitnet->save($export);
        } catch (\PDOException $e) {
            $this->inError($export, 'Erreur à la sauvegarde de l\'état final de l\'export');
        }

        $this->file_log->close();

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

    protected function setFitnetLink( $url, $object ){
        //récupération des lgoin/mdp du compte admin de fitnet
        $username = Configure::read('fitnet.login');
        $password = Configure::read('fitnet.password');
        $return = false;

        // instance Client pour gestin des appel ajax
        $http = new Client();
        // construction de l'url fitnet
        $base = Configure::read('fitnet.base');
        if (substr($url, 0, 1) == "/" ) {
            $url = substr($url, 1);
        }
        $url=$base . $url ;

        // appel de la requête
        $response = $http->post($url, $object, [ 'auth'=>['username' => $username, 'password' => $password], 'type' => 'json' ]);
        if ($response->isOk()) {
            $result = $response->json;
            // $result = true;
        }else {
            $this->inError(null, 'Erreur sur requête fitnet, code erreur : '.$response->getStatusCode(), $response->getStatusCode());
        }

        // résultat
        return $result;
    }

    // **TEST POUR APPEL FITNET**
    public function setTimeFitnetShell(){
        $result = [];

        $timesheet = [
            "activity" => "",
            "activityID" => 0,
            "activityType" => 1,
            "amount" => 1,
            "assignmentDate" => "22/08/2018",
            "assignmentID" => 245,
            "company" => "",
            "companyID" => 1,
            "employee" => "",
            "employeeID" => 38,
            "remark" => "",
            "timesheetAssignmentID" => 0,
            "typeOfService" => "",
            "typeOfServiceID" => 0 // @TODO read config pour obtenir le bon profilID
        ];

        $timesheetJS = json_encode($timesheet);

        $url = '/FitnetManager/rest/timesheet';
        $result = $this->setFitnetLink($url, $timesheetJS);

        // type de réponse : objet json
        $this->response->type('json');
        // contenue de la réponse
        $this->response->body(json_encode($result));

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
