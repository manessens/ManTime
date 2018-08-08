<?php
namespace App\Shell;

use Cake\Console\Shell;
use App\Controller\ProjetController;

class FitnetShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadModel('Client');
    }

    public function main()
    {
        // $this->out('Hello world.');
        if (empty($this->args[0])) {
            return $this->abort("Merci de rentrer un email d'utilisateur.");
        }

        $projet = new ProjetController();
        // $projet->constructClasses();
        $found = $projet->getProjectFitnet();

        // $this->out(print_r($found, true));
        $this->createFile('report.json', $found);

        // $user = $this->Users->findByEmail($this->args[0])->first();
        // $this->out(print_r($user, true));

        // $this->createFile('report.json', json_encode($user));

        // ***************************************************************
        // $found = [];
        //
        // // récupération des id company fitnet
        // $client = $this->Client->findByIdc($id_client, [
        //     'contain' => ['Agence']
        // ]);
        //
        // $id_fit = $client->agence->id_fit;
        //
        // // séparation des id_agence fitnet
        // $ids = explode(';', $id_fit);
        //
        // // appel de la requête
        // $result = $this->getFitnetLink("/FitnetManager/rest/projects/".$this->args[0]);
        // // décode du résultat json
        // $vars = json_decode($result, true);
        // // sauvegarde des résultats trouvés
        // $found = array_merge($found, $vars);
        //
        // $select2 = ['select' => array(), 'projects' => array()];
        // //remise en forme du tableau
        // foreach ($found as $value) {
        //     if ($value['customer'] == $client->id_fit or $client->id_fit == null) {
        //         $select2['select'][]=array('id'=>$value['forfaitId'], 'text'=>$value['title']);
        //         $select2['projects'][$value['forfaitId']]=$value;
        //     }
        // }
        //
        // // réencodage pour renvoie au script ajax
        // $json_found = json_encode($select2);
        //
        // $this->createFile('report.json', json_encode($json_found));
    }
}
