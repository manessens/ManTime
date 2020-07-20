<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Form\ExportForm;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Http\Client;
use Cake\Controller\Component\CookieComponent;
use Cake\Console\ShellDispatcher;

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
    var $arrayAssignMemory;
    var $delimiteur;

    public function initialize()
    {
        parent::initialize();
        $this->data_log = array();
        $this->error_log = array();
        $this->arrayAssignMemory = array();
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
        $this->loadModel('Client');
        $arrayClient = $this->Client->find('all')->contain(['Agence'])->toArray();
        $clients = array();
        $agenceClient = array();
        foreach ($arrayClient as $client) {
            $clients[$client->idc] = ucfirst($client->nom_client);
        }
        $this->loadModel('Users');
        $arrayUser = $this->Users->find('all')->contain(['Origine'])->toArray();
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
                $arrayData['etat'] = Configure::read('vsa.wait');
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
        $filename = Configure::read('vsa.logname_end') . $id . '.csv';
        $absFileName = Configure::read('vsa.abs_path').Configure::read('vsa.logdir_end').DS.$filename;
        if (!file_exists($absFileName)) {
            $filename = Configure::read('vsa.logname') . $id . '.csv';
            $absFileName = Configure::read('vsa.abs_path').Configure::read('vsa.logdir').DS.$filename;
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
        if ($export->etat == Configure::read('vsa.wait') ) {
            try {
                $this->ExportFitnet->delete($export);
                $this->Flash->success(__('La demande d\'export a été supprimé avec succés.'));
            } catch (\PDOException $e) {
                $this->Flash->error(__("La demande d'export n'a pus être supprimé. Assurez-vous qu'il ne soit pas utilisé avant de réessayer."));
            }
        }elseif ($export->etat == Configure::read('vsa.err')) {
            $export->etat = Configure::read('vsa.nerr');
            $this->ExportFitnet->save($export);
            $this->Flash->success(__('La demande d\'export en erreur à été fixé.'));
        }else{
            $this->Flash->error(__("La demande d'export n'a pus être supprimé, celle-ci est soit terminée soit en cours de traitement."));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function manuel($id = null){
        $bash = "cake Fitnet ".$id;

        $this->loadComponent('Cookie');
        $dataCo = $this->Cookie->read('Authvsa');

        $token = $dataCo['token'];
        $result = false;

        Configure::write('vsa.token', $token);

        $vars = $this->getVsaLinkExport("/v1/app/version?type=ALL");
        // $vars = json_decode($resultTest, true);
        if (!is_array($vars)) {
            $this->Flash->error("Les informations de connexion n'ont pas permis l'utilisation des API Fitnet.");
            return $this->redirect(['action' => 'index']);
        }
        if(array_key_exists('error', $vars)){
            $this->Flash->error("Les informations de connexion n'ont pas permis l'utilisation des API Fitnet.");
            return $this->redirect(['action' => 'index']);
        }

        $shell = new ShellDispatcher();
        $output = $shell->run(['cake', 'Fitnet', $id]);

        if (0 === $output) {
            $this->Flash->success('Le script bash a été exécuté.');
        } else {
            $this->Flash->error("Une erreur est survenu lors de l'écxécution du script bash.");
        }

        Configure::write('vsa.token', "");

        return $this->redirect(['action' => 'index']);
    }

    private function getExportActif(){
        return $this->ExportFitnet->find('all')->where(['etat =' => Configure::read('vsa.wait')])->toArray();
    }

    private function getExportId($id){
        if ($id == null) {
            return [];
        }
        return $this->ExportFitnet->find('all')->where(['etat =' => Configure::read('vsa.wait')])->orWhere(['etat =' => Configure::read('vsa.err') ])->andWhere(['id_fit =' => $id])->toArray();
    }

    private function getTimesFromExport($export){

        $date_debut = Time::parse($export->date_debut);
        $date_fin = Time::parse($export->date_fin);
        $data_client = $export->idc;
        $data_user =  $export->idu;

        $times = array();
        $data = array();
        $periodes = array();
        $semaineDebut = (int)date('W', strtotime($date_debut->i18nFormat('dd-MM-YYYY')));
        $anneeDebut = (int)date('Y', strtotime($date_debut->i18nFormat('dd-MM-YYYY')));
        $semaineFin = (int)date('W', strtotime($date_fin->i18nFormat('dd-MM-YYYY')));
        $anneeFin =   (int)date('Y', strtotime($date_fin->i18nFormat('dd-MM-YYYY')));

        $arraNSem = array($anneeDebut => array());

        $y=$anneeDebut;
        for ($i=$semaineDebut; ($i <= $semaineFin && $y <= $anneeFin) ; $i++) {
            if ($i > 52) {
                $i = 1;
                $y++;
            }
            $arraNSem[$y][] = $i;
        }
        $query = null;
        $this->loadModel('Exportable');
        $query = $this->Exportable->find('all');
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
            $this->loadModel('Temps');
            $query = $this->Temps->find('all')->contain(['Projet'=>['Client'=>'Agence', 'Facturable'], 'Users'=>['Origine'], 'Profil'])
                ->where([
                    'date >=' => $date_debut, 'date <=' => $date_fin,
                    'validat =' => 1,
                    'deleted =' => false,
                    'Projet.id_fit  IS NOT' => null,
                    'Client.id_fit  IS NOT' => null,
                    'Users.id_fit IS NOT' => null
                 ] )
                ->andwhere(['OR' => $andWhere]);

            if ( $data_client != null) {
                $this->loadModel('Projet');
                $arrayIdProjet = $this->Projet->find('list',['fields' =>['idc','idp']])->where(['idc =' => $data_client])->toArray();
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
            $line = ['##', ' ERREUR -- VSA : ', $cause];
        }elseif ($code != null) {
            $line = ['##', ' VSA code retour : '.$code, $cause];
        }else{
            $line = ['##', ' ERREUR -- Syn\'chrone : ', $cause];
        }
        $this->insertLog($line, true);

        return $export;
    }

    private function inProcess($export){

        $filename = Configure::read('vsa.logname') . $export->id_fit . '.csv';

        $folder = new Folder(Configure::read('vsa.abs_path').Configure::read('vsa.logdir'));
        $this->file_log = new File($folder->pwd() . DS . $filename);
        $this->file_log->delete();

        $export->etat = Configure::read('vsa.run');
        // Notification de lancement du traitemnt
        $line = ['>>', ' Début du traitement EXPORT VSA pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($line);

        $this->ExportFitnet->save($export);
        return $export;
    }

    private function writeLog($id){
        // Ecrit une nouveau log pour l'export #$id
        $filename = Configure::read('vsa.logname_end') . $id . '.csv';

        $folder = new Folder(Configure::read('vsa.abs_path').Configure::read('vsa.logdir_end'));
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
        $count = $ignored = 0;
        if (empty($times)) {
            // notif export : erreur si 0 temps - FIN de traitement
            $export=$this->inError($export, 'Aucun temps trouvé sur la sélection');
            $this->ExportFitnet->save($export);
        }else{
            //traitement des Temps pour fusion des lignes
            $tmpTimeSum = array();
            foreach ($times as $tempTime) {
                $keyDate = $tempTime->date->i18nFormat('yyyy-MM-dd');
                $keyUser = $tempTime->user->id_fit;
                $keyClient = $tempTime->projet->client->id_fit;
                $keyProject = $tempTime->projet->id_fit;
                $keyProfil = Configure::read('vsa.profil.'.$tempTime->id_profil);
                if (!array_key_exists($keyUser, $tmpTimeSum)) {
                    $tmpTimeSum[$keyUser] = array();
                }
                if (!array_key_exists($keyDate, $tmpTimeSum[$keyUser])) {
                    $tmpTimeSum[$keyUser][$keyDate] = array();
                }
                if (!array_key_exists($keyClient, $tmpTimeSum[$keyUser][$keyDate])) {
                    $tmpTimeSum[$keyUser][$keyDate][$keyClient] = array();
                }
                if (!array_key_exists($keyProject, $tmpTimeSum[$keyUser][$keyDate][$keyClient])) {
                    $tmpTimeSum[$keyUser][$keyDate][$keyClient][$keyProject] = array();
                }
                if (!array_key_exists($keyProfil, $tmpTimeSum[$keyUser][$keyDate][$keyClient][$keyProject])) {
                    $tmpTimeSum[$keyUser][$keyDate][$keyClient][$keyProject][$keyProfil] = ["time"=> 0, "used"=>false];
                }
                $tmpTimeSum[$keyUser][$keyDate][$keyClient][$keyProject][$keyProfil]["time"] += $tempTime->time;
            }

            //récupération de la liste des assignements
            $assignements = $this->getAssignements();
            debug($assignements);

            //traitement des Temp
            foreach ($times as $time) {
                if ($time->projet->facturable->id_fit == 0) {
                    $line = ['--', ' Export des activités de type '.$time->projet->facturable->nom_fact.' ignorées : temps #'.$time->idt.' - '.$time->user->fullname.' |Date : '.$time->date];
                    $this->insertLog($line);
                    $ignored++; //car n'est pas une erreur
                }else{
                    $count++;
                    $timesheets = $this->exportTime($time, $tmpTimeSum, $assignements);
                    if (is_array($timesheets['time'])) {
                        $timeSheets[] = $timesheets['time'];
                        $delTimes[] = $timesheets['delete'];
                        $names[$time->user->id_fit] = $time->user->fullname;
                    }
                }
            }

            //SUPPRESSION
            $url = '/v1/activity/timesheet';
            $resultd = $this->setVsaLink($url, "DELETE", $delTimes);
            if (is_array($resultd)) {
                if (array_key_exists('error', $resultd)) {
                    foreach ($resultd['data'] as $key => $message) {
                        preg_match ( '/[0-9]+/' , $key , $matches );
                        if (is_array($matches)) {
                            $deleteTime = $delTimes[$matches[0]-1];
                            foreach ($message[0] as $k => $v) {
                                $msgError = $v.
                                ' : |Consultant: '.$names[$deleteTime['userId']].
                                ' |Affaire: '.$deleteTime['orderId'].
                                ' |Profil: '.$deleteTime['deliveryCode'].
                                ' |Date: '.$deleteTime['date'];
                                $export = $this->inError($export, $msgError);
                            }
                        }
                    }
                }
            }
            //ENREGISTREMENT
            $url = '/v1/activity/timesheet';
            $result = $this->setVsaLink($url, "POST", $timeSheets);
            // Création du message d'erreur si nécessaire"
            if (is_array($result)) {
                if (array_key_exists('error', $result)) {
                    foreach ($result['data'] as $key => $message) {
                        preg_match ( '/[0-9]+/' , $key , $matches );
                        if (is_array($matches)) {
                            $time = $timeSheets[$matches[0]-1];
                            foreach ($message[0] as $k => $v) {
                                $msgError = $v.
                                ' : |Consultant: '.$names[$time['userId']].
                                ' |Client: '.$time['tiersCode'].
                                ' |Affaire: '.$time['orderCode'].
                                ' |TabTitle: '.$time['tabTitle'].
                                ' |Profil: '.$time['deliveryCode'].
                                ' |Date: '.$time['date'].
                                ' |Valeur: '.$time['quantityDay'].'J ';
                                $count--;
                                $export = $this->inError($export, $msgError);
                            }
                        }
                    }
                }
            }
        }
        $export=$this->endProcess($export, $count, count($times), $ignored);

    }

    // public function testVsa(){
    //
    //     $this->loadComponent('Cookie');
    //     $dataCo = $this->Cookie->read('Authvsa');
    //     Configure::write('vsa.token', $dataCo['token']);
    //
    //     $timeSheets=[];
    //     $timeSheets[] = [
    //         "userId" => 1645,
    //         "tiersCode" => "C-BIOLINE_BY_INVIVO",
    //         "orderCode" => "PAR.AAL.201907.C.0165",
    //         "tabTitle" => "INVIVO Mainonline 2.0",
    //         "deliveryCode" => "CDSTMA",
    //         "date" => "2020-01-06",
    //         "moment" => "J",
    //         "quantityDay" => 1,
    //         "quantityHour" => 8,
    //         "comment" => ""
    //     ];
    //
    //     $url = '/v1/activity/timesheet';
    //     $result = $this->setVsaLink($url, "POST", $timeSheets);
    //
    //     Configure::write('vsa.token', "");
    //
    //     debug($result,true);
    //     exit;
    // }

    public function getAssignements(){
        return $this->getVsaLinkExport("v1/orders/assignments");
    }

    public function findAssignements($assignements, $projet, $userEmail, $keyClient, $keyProfil){
        $orderCode = explode('|', $projet->id_fit)[1];
        $key = $keyClient . $orderCode . $keyProfil . $userEmail;

        if (array_key_exists($key, $this->arrayAssignMemory)) {
            return $this->arrayAssignMemory[$key];
        }
        debug($assignements);
        foreach ($assignements as $assignement) {
            if ($assignement['tiersCode'] != $keyClient
                || $assignement['orderCode'] != $orderCode
                || $assignement['prestation'] != $keyProfil
                || $assignement['colLogin'] != $userEmail
                || $assignement['startDate'] >=  $projet->date_debut
                || $assignement['endDate'] <=  $projet->date_fin ) {
                continue;
            }
            $this->arrayAssignMemory[$key] = $assignement['tabTitle'];
            return $assignement['tabTitle'];
            break;
        }
    }

    private function exportTime($time, $tmpTimeSum, $assignements = []){
        $noError = true;
        if (empty($time)) {
            return false;
        }

        // Gen key for time
        $keyProfil = Configure::read('vsa.profil.'.$time->id_profil);
        $keyClient = $time->projet->client->id_fit;
        $keyProject = $time->projet->id_fit;

        $tabProject = $this->findAssignements($assignements, $time->projet, $time->user->email, $keyClient, $keyProfil);
        // str_replace('_', '.', $time->projet->nom_projet);

        // Date
        $assignementDate = $time->date->i18nFormat('yyyy-MM-dd');

        // Contrôle Projet
        if ($keyProject == null) {
            $this->insertLog(['--','Le projet '.$time->projet->nom_projet."n'est pas lié à une affaire fitnet : pas d'export"]);
            $noError = false;
        }
        // Contrôle Client
        if ($keyClient == null) {
            $this->insertLog(['--', 'Client non lié : '. $time->projet->client->nom_client] );
            $noError = false;
        }
        // Contrôle Utilisateur
        $employeeID = $time->user->id_fit;
        if ($employeeID == null) {
            $this->insertLog(['--', 'Utilisateur non lié : '. $time->user->fullname] );
            $noError = false;
        }
        // // companyID
        // $companyID = $time->projet->client->agence->id_fit;
        // if ($companyID == null) {
        //     $this->inError(null, 'ID_FIT de la table Agence est éronné pour la ligne #'. $time->projet->client->agence->id_agence );
        //     $noError = false;
        // }
        // // Contrôle activityType
        // $activityType = $time->projet->facturable->id_fit;
        // if ($activityType == null) {
        //     $this->inError(null, 'ID_FIT de la table Facturable est éronné pour la ligne #'. $time->projet->facturable->idf );
        //     $noError = false;
        // }

        // Contrôle traité par cumul des temps (multiligne sur même assignement)
        if ($tmpTimeSum[$employeeID][$assignementDate][$keyClient][$keyProject][$keyProfil]["used"]) {
            $this->insertLog(['--','Le temps #'.$time->idt." |Consultant : #".$time->user->fullname.' |Projet : '.$time->projet->nom_projet.' |Date : '.$time->date." a été traité par cumul."]);
            return true; // car n'est pas une erreur et on return maintenant pour éviter le contrôle de l'assignement
        }

        // // Récupération des assignement
        // $assignementID = $this->getAssignement($time);
        // if ($assignementID == null) {
        //     $this->inError(null, 'Aucun assignement trouvé pour le Temps |Consultant : '.$time->user->fullname.
        //                 ' |Client : '.$time->projet->client->nom_client.' |Projet : '.$time->projet->nom_projet.' |Date : '. $time->date->i18nFormat('dd-MM-yy') );
        //     $noError = false;
        // }

        // Contrôle d'erreur
        if (!$noError) {
            return $noError;
        }

        // total temps travaillé
        $amount = $tmpTimeSum[$employeeID][$assignementDate][$keyClient][$keyProject][$keyProfil]["time"];

        $keysproject = explode('|', $keyProject);

        $delTime = [
            "userId" => $employeeID,
            "orderId" =>  $keysproject[0],
            "deliveryCode" => $keyProfil,
            "date" => $assignementDate
        ];
        $timesheet = [
            "userId" => $employeeID,
            "tiersCode" => $keyClient,
            "orderCode" => $keysproject[1],
            "tabTitle" => $tabProject,
            "deliveryCode" => $keyProfil,
            "date" => $assignementDate,
            "moment" => "J",
            "quantityDay" => $amount,
            "quantityHour" => ($amount * 8),
            "comment" => $time->detail
        ];

        $tmpTimeSum[$employeeID][$assignementDate][$keyClient][$keyProject][$keyProfil]["used"] = true;

        return ['delete'=>$delTime, 'time'=>$timesheet];
    }

    private function endProcess($export, $count, $total, $ignored = 0){
        if ($count != $total) {
            $cause = 'nombre de saisie échoué : '.($total-($count+$ignored) );
            $line = ['##', ' EXPORT -- ID #'.$export->id_fit, $cause];
            $this->insertLog($line,true,true);
        }
        if (empty($this->error_log)) {
            $export->etat = Configure::read('vsa.end');
        }else{
            $export->etat = Configure::read('vsa.err');
        }

        $this->insertLog(['--', ' Total de temps traité : '.$total]);
        $this->insertLog(['--', ' Total de temps exporté avec succés : '.$count]);
        $this->insertLog(['--', ' Total de temps ignorées : '.$ignored]);

        $line = ['<<', ' Fin du traitement EXPORT VSA pour la demande d\'export #'.$export->id_fit];
        $this->insertLog($line);


        try {
            $this->ExportFitnet->save($export);
        } catch (\PDOException $e) {
            $this->inError($export, 'Erreur à la sauvegarde de l\'état final de l\'export');
        }

        $this->file_log->close();

    }

    public function launchExport($id = null){
        if ($id === null) {
            $exports = $this->getExportActif();
        }else{
            $exports = $this->getExportId($id);
        }
        if (empty($exports)) {
            return('Empty exports');
        }
        foreach ($exports as $export) {

            $this->data_log = array();
            $this->error_log = array();

            $this->processExport($export);

            $this->writeLog($export->id_fit);
        }
        return('OK');
    }

    protected function getVsaLinkExport( $url, $rest = 'GET' ){
        //récupération des lgoin/mdp du compte admin de fitnet

        $token = Configure::read('vsa.token');

        // préparation de l'en-tête pour la basic auth de fitnet
        $opts = array(
          'http'=>array(
                'method'=>$rest,
                'header'=>"Authorization: Bearer " . $token
              )
        );
        // ajout du header dans le contexte
        $context = stream_context_create($opts);
        // construction de l'url vsa
        $base = Configure::read('vsa.base');
        if (substr($url, 0, 1) == "/" ) {
            $url = substr($url, 1);
        }
        $url=$base . $url ;

        // appel de la requête
        $result = @file_get_contents($url, false, $context);
        if($result === false){
            $result = 'error';
        }

        // résultat
        if (!is_array($result)) {
            $result = json_decode($result);
        }
        return $result;
    }

    protected function setVsaLink( $url, $rest, $object ){

        $token = Configure::read('vsa.token');
        $result = false;
        $errors = [];

        // construction de l'url vsa
        if (substr($url, 0, 1) == "/" ) {
            $url = substr($url, 1);
        }
        $base = Configure::read('vsa.base');
        $url=$base . $url ;

        // appel de la requête
        // ENREGISTREMENT
        $authorization = "Authorization: Bearer ".$token;
        $ch = curl_init( $url );
        # Setup request to send json via POST.
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($object) );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', $authorization));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        // résultat
        return $result;
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['export', 'index', 'add', 'delete', 'view', 'manuel']) && $user['role'] >= Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
