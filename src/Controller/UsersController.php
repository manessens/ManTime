<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Mailer\Email;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['logout']);
    }

    public function password($id = null)
    {
        $user =$this->Users->get($this->Auth->user('idu'));
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            $user['prem_connect'] = 0;
            if ($this->Users->save($user)) {
                $this->Flash->success('Le mot de passe à été modifié avec succées');
                $this->Auth->setUser($user);
                return $this->redirect(['controller'=>'board', 'action' => 'index']);
            } else {
                $this->Flash->error("Une erreur c'est produit à la sauvegarde !");
            }
            $this->Flash->error(__("Erreur d'identification."));
        }
        $this->set(compact('user'));
    }

    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if ($user['actif']) {
                    $this->Auth->setUser($user);
                    // return $this->redirect($this->Auth->redirectUrl());
                    if ($user['prem_connect']) {
                        $this->set('nomenu','true');
                        return $this->redirect(['controller' => 'Users', 'action' => 'password']);
                    }
                    return $this->redirect(['controller'=>'board', 'action' => 'index']);
                }
                $this->Flash->error("Votre compte n'est pas actif.");
                return;
            }
            $this->Flash->error('Votre identifiant ou votre mot de passe est incorrect.');
        }
    }

    public function logout()
    {
        $this->Flash->success('Vous avez été déconnecté.');
        return $this->redirect($this->Auth->logout());
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => ['Articles']
        ]);

        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Le consultant à été sauvegardé.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le consultant ne peut être sauvegarder. Veuillez retenter ultérieurement.'));
        }
        $this->set(compact('user'));
    }

    public function test()
    {
        $userAuth = $this->Auth->identify();
        $email = new Email('default');
        $email->from([ 'matthias.vincent@manessens.com' => 'My Site'])
            ->to('matthias.vincent@manessens.com')
            ->subject('About')
            ->send('My message');
        // $email = new Email();
        // $email->transport('default')
        //       ->template('test')
        //       ->emailFormat('both')
        //       ->to($user->email)
        //       ->subject('bienvenu sur ManTime !')
        //       ->from($userAuth['email']);
        // $email->viewVars([ 'content' => ['test qsdf qs', 'sdqfqsdfsd qsdf q'] ]);
        // $email->send();
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($user['prem_connect']) {
                $user['mdp'] = 'Welcome1!';
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Le consultant à été sauvegardé.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le consultant ne peut être sauvegarder. Veuillez retenter ultérieurement.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('Le consultant a bien été supprimé.'));
        } else {
            $this->Flash->error(__('Le consultant ne peut être supprimé. Veuillez retenter ultérieurement.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['password']) ) {
            return true;
        }
        if (in_array($action, ['test']) ) {
            return true;
        }
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if (in_array($action, ['index', 'view', 'add', 'edit','delete']) && $user['admin'] === 1 ) {
            return true;
        }

        return false;
    }
}
