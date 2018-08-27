<?php

use Cake\Error\Debugger;
use Cake\Core\Configure;

namespace App\Controller;

class UtilsController extends AppController
{
    public function index()
    {
        // $user_id = $this->request->session()->read('Auth.User')['idu'];
        $user_id = $this->Auth->user('idu');
        $this->loadModel('Users');
        $user = $this->Users->findByIdu($user_id)->firstOrFail();
        $this->set('controller', false);
        $this->set(compact('user'));
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        // si réinitialisation de mdp nécessaire on reourne faux
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index']) && $user['role'] >= Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
