<?php

use Cake\Error\Debugger;

namespace App\Controller;

class BoardController extends AppController
{
    public function index()
    {

        // $user_id = $this->request->session()->read('Auth.User')['idu'];
        $user_id = $this->Auth->user('idu');
        $this->loadModel('Users');
        $user = $this->Users->findByIdu($user_id);
        // $user = $this->Auth->identify();
        pr($user->prenom);exit;
        $this->set(compact('user'));
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // Les actions 'add' et 'tags' sont toujours autorisés pour les utilisateur
        // authentifiés sur l'application
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index']) ) {
            return true;
        }

        return false;
    }

}
