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
        // $this->out('Hello world.');
        // if (empty($this->args[0])) {
        //     return $this->abort("Merci de saisir un id de projet.");
        // }

        $exportFitnet = new ExportFitnetController();
        $found = $exportFitnet->launchExport();
        // $found = $exportFitnet->getProjectFitnetShell($this->args[0]);

        // $this->createFile('fitnet_log/report.json', $found);

        // $user = $this->Users->findByEmail($this->args[0])->first();
        // $this->out(print_r($user, true));
    }
}
