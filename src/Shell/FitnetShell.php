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
            return $this->abort("Merci de saisir un id de projet.");
        }

        $projet = new ProjetController();
        $found = $projet->getProjectFitnet($this->args[0]);

        $this->out(print_r($this->args[0], true));
        $this->out(print_r($found, true));
        $this->createFile('report.json', $found);

        // $user = $this->Users->findByEmail($this->args[0])->first();
        // $this->out(print_r($user, true));
    }
}
