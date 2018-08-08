<?php
namespace App\Shell;

use Cake\Console\Shell;

class FitnetShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
        $this->loadController('Temps');
    }

    public function main()
    {
        // $this->out('Hello world.');
        if (empty($this->args[0])) {
            return $this->abort("Merci de rentrer un nom d'utilisateur.");
        }
        $user = $this->Users->findByEmail($this->args[0])->first();
        // $this->out(print_r($user, true));
        $tempsController = new TempsController();
        $tempsController->constructClasses(); //I needed this in here for more complicated requiring component loads etc in the Controller
        $tempsController->test();
        $this->createFile('report.json', json_encode($user));
    }
}
