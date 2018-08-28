<?php

namespace App\Controller;

use App\Controller\AppController;


class UtilsController extends AppController
{
    public function index()
    {
        $current = (int)date('W');
        $this->loadModel('Users');
        if ($this->request->is('post')) {

        }
        if ($semaine === null) {
            $semaine = $current;
        }
        if ($annee === null) {
            $annee = date('Y');
        }

        $this->set('controller', false);
        $this->set(compact('users'));
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        // si réinitialisation de mdp nécessaire on reourne faux
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index']) && $user['role'] >= \Cake\Core\Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
