<?php
namespace App\Shell;

use Cake\Console\Shell;

class HelloShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Users');
    }

    public function main()
    {
        $this->out('Hello world.');
    }

    public function show(){
        if (empty($this->args[0])) {
            // Utilisez error() avant CakePHP 3.2
            return $this->abort("Merci de rentrer un nom d'utilisateur.");
        }
        $user = $this->Users->findByEmail($this->args[0])->first();
        $this->out(print_r($user, true));
    }
}
