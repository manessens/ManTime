<?php

namespace App\Controller;

use App\Controller\AppController;
use Cake\I18n\Date;


class UtilsController extends AppController
{
    public function index()
    {
        $semaine = null;
        $annee = null;
        if ($this->request->is('post')) {
            $arrayData = $this->request->getData();
            $semaine = $arrayData['week'];
            $annee = $arrayData['year'];
        }
        $current = (int)date('W');
        if ($semaine === null) {
            $semaine = $current;
        }
        if ($annee === null) {
            $annee = date('Y');
        }
        $lundi = new Date('now');
        $lundi->setTime(00, 00, 00);
        $lundi->setISOdate($annee, $semaine);
        $dimanche = clone $lundi;
        $dimanche->modify('+7 days');

        $this->loadModel('Users');
        $usersV = $this->Users->find('all')
            ->innerJoinWith('Temps', function ($q) use ($lundi, $dimanche) {
                return $q->where(['Temps.date >=' => $lundi, 'Temps.date <' => $dimanche, 'Temps.validat =' => 1]);
            })
            ->distinct(['Users.idu'])
            ->toArray();
        $usersN = $this->Users->find('all')
            ->innerJoinWith('Temps', function ($q) use ($lundi, $dimanche) {
                return $q->where(['Temps.date >=' => $lundi, 'Temps.date <' => $dimanche, 'Temps.validat =' => 0]);
            })
            ->distinct(['Users.idu'])
            ->toArray();

        $this->set('controller', false);
        $this->set(compact('semaine'));
        $this->set(compact('annee'));
        $this->set(compact('usersV'));
        $this->set(compact('usersN'));
    }

    public function setActivUser(){
        if( $this->request->is('ajax') ) {
            $this->autoRender = false; // Pas de rendu
        }
        if ($this->request->is(['POST'])) {
            $id_user = $this->request->query["user"];
        }
        return $this->response->withStringBody($id_user);
    }

    public function setUnactivUser(){
        if( $this->request->is('ajax') ) {
            $this->autoRender = false; // Pas de rendu
        }
        if ($this->request->is(['POST'])) {
            $id_user = $this->request->query["user"];
        }
        return $this->response->withStringBody($id_user);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        // si réinitialisation de mdp nécessaire on reourne faux
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index', 'setUnactivUser', 'setActivUser']) && $user['role'] >= \Cake\Core\Configure::read('role.admin') ) {
            return true;
        }

        return false;
    }

}
