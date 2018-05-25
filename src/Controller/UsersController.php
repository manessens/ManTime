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
        $this->paginate = [
            'contain'   => ['Origine'],
            'sortWhitelist' => [
                'Users.prenom', 'Users.nom', 'Users.email', 'Origine.nom_origine', 'Users.actif', 'Users.role'
            ],
            'order' => [
                'Users.prenom' => 'asc'
            ]
        ];

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
                $this->Flash->success('Le mot de passe à été modifié avec succés');
                $this->Auth->setUser($user);
                return $this->redirect(['controller'=>'board', 'action' => 'index']);
            } else {
                $this->Flash->error("Une erreur c'est produite à la sauvegarde !");
            }
        }
        $this->set(compact('user'));
        $this->set('controller', false);
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
            'contain' => ['Origine']
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
        $origineOption = $this->getOrigineOption();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($user['admin']) {
                $user['role'] = 50;
            }elseif ($user['role']) {
                $user['role'] = 20;
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Le consultant à été sauvegardé.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le consultant ne peut être sauvegarder. Veuillez retenter ultérieurement.'));
        }
        $user->admin = $user->role >= 50;
        $user->role = $user->role >= 20;
        $this->set(compact('user'));
        $this->set(compact('origineOption'));
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

    private function getOrigineOption()
    {
        $origineTable = TableRegistry::get('Origine');
        $origineOption = array();
        $origineOption = $origineTable->find('list',['fields'=>['ido', 'nom_origine']])->toArray();
        return $origineOption;
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
        $user = $this->Users->get($id);
        $origineOption = $this->getOrigineOption();
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($user['prem_connect']) {
                $user['mdp'] = 'Welcome1!';
            }
            if ($user['admin']) {
                $user['role'] = 50;
            }elseif ($user['role']) {
                $user['role'] = 20;
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Le consultant à été sauvegardé.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le consultant ne peut être sauvegarder. Veuillez retenter ultérieurement.'));
        }
        $user->admin = $user->role >= 50;
        $user->role = $user->role >= 20;
        $this->set(compact('user'));
        $this->set(compact('origineOption'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function profil()
    {
        $idUserAuth = $this->Auth->user('idu');
        $user = $this->Users->get($idUserAuth, [
            'contain' => ['Origine']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($user['prem_connect']) {
                $user['mdp'] = 'Welcome1!';
            }
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Votre profil à été sauvegardé.'));

                return $this->redirect(['controller' => 'Board','action' => 'index']);
            }
            $this->Flash->error(__('Votre profil ne peut être sauvegarder. Veuillez retenter ultérieurement.'));
        }
        $this->set(compact('user'));
        $this->set('controller', false);

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
        $user->actif = 0;
        if ($this->Users->save($user)) {
            $this->Flash->success(__('Le consultant à été désactivé.'));
        }else{
            $this->Flash->error(__('Erreur à la modification du consultant. Veuillez retenter ultérieurement.'));
        }
        // if ($this->Users->delete($user)) {
        //     $this->Flash->success(__('Le consultant a bien été supprimé.'));
        // } else {
        //     $this->Flash->error(__('Le consultant ne peut être supprimé. Veuillez retenter ultérieurement.'));
        // }

        return $this->redirect(['action' => 'index']);
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['password']) && $user['prem_connect']) {
            return true;
        }
        // if (in_array($action, ['test']) ) {
        //     return true;
        // }
        if ($user['prem_connect'] === 1) {
            return false;
        }

        if ( in_array($action, ['profil']) ) {
            return true;
        }

        if (in_array($action, ['index', 'view', 'add', 'edit','delete']) && $user['role'] >= 50 ) {
            return true;
        }

        return false;
    }
}
