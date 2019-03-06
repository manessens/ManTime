<?php
namespace App\Shell;

use Cake\Console\Shell;
use App\Controller\ExportFitnetController;

class FitnetShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        // $this->loadModel('Users');
        $this->loadModel('Client');
    }

    public function main()
    {
        // Appel du controller FitnetController
        $exportFitnet = new ExportFitnetController();
        if (empty($this->args[0])) {
            $found = $exportFitnet->launchExport();
        }else{
            $this->out('Execute mod : manuel, Id : '.$this->args[0]);
            $found = $exportFitnet->launchExport($this->args[0]);
        }
        // Lancement de l'export

        // test d'échange fitnet :
        // $found = $exportFitnet->setTimeFitnetShell();

    }

    // public function test(){
    //     // Test de la présence d'un argument
    //     // $this->out('Hello world.');
    //     // if (empty($this->args[0])) {
    //     //     return $this->abort("Merci de saisir un id de projet.");
    //     // }
    //
    //     // écriture dans le bash
    //     // $this->out(print_r($user, true));
    //
    //     //  Ecriture dans un fichier
    //     // $this->createFile('fitnet_log/report.json', $found);
    //
    // }
}
