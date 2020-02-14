<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Controller\Component\CookieComponent;


/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    protected function getVsaLogin(){
        //récupération des lgoin/mdp du compte admin de fitnet

        // ****** authentification par interface  ******{
        $this->loadComponent('Cookie');
        $dataCo = $this->Cookie->read('Authvsa');

        $login = $dataCo['login'];
        $pass = $dataCo['password'];
        // ****** authentification par interface  ******

        // préparation de l'en-tête
        $opts = array(
          'http'=>array( 'method'=>"GET" )
        );
        // ajout du header dans le contexte
        $context = stream_context_create($opts);
        // construction de l'url login VSA
        $base = Configure::read('vsa.base');
        $url=$base.'login?login='.$login.'&password='.$pass ;
        // appel de la requête
        $result = @file_get_contents($url, false, $context);
        if($result === FALSE){
            return false;
        }
        $vars = json_decode($result, true);
        if (is_array($vars)) {
            if (array_key_exists('token', $vars)) {
                $this->Cookie->write('Authvsa', $vars);
                return true;
            }
        }

        // résultat
        return false;
    }

    protected function getVsaLink( $url , $rest = "GET" ){
        //récupération des lgoin/mdp du compte admin de fitnet

        $this->loadComponent('Cookie');
        $dataCo = $this->Cookie->read('Authvsa');

        $token = $dataCo['token'];

        // préparation de l'en-tête pour la basic auth de fitnet
        $opts = array(
          'http'=>array(
                'method'=>$rest,
                'header'=>"Authorization: Bearer " . $token
              )
        );
        // ajout du header dans le contexte
        $context = stream_context_create($opts);
        // construction de l'url fitnet
        $base = Configure::read('vsa.base');
        if (substr($url, 0, 1) == "/" ) {
            $url = substr($url, 1);
        }
        $url=$base . $url ;
        return $url;
        // appel de la requête
        $result = @file_get_contents($url, false, $context);
        if($result === FALSE){
            $result = 'error';
        }

        // résultat
        return $result;
    }

    protected function getFitnetLink( $url , $noSafe = null){
        //récupération des lgoin/mdp du compte admin de fitnet

        // ****** authentification par interface temporaire ******
        if ($noSafe !== null) {
            $username = Configure::read('fitnet.login');
            $password = Configure::read('fitnet.password');
        }else{
            $this->loadComponent('Cookie');
            $dataCo = $this->Cookie->read('Authfit');

            $username = $dataCo['login'];
            $password = $dataCo['password'];
        }
        // ****** authentification par interface temporaire ******

        // préparation de l'en-tête pour la basic auth de fitnet
        $opts = array(
          'http'=>array(
                'method'=>"GET",
                'header'=>"Authorization: Basic " . base64_encode("$username:$password")
              )
        );
        // ajout du header dans le contexte
        $context = stream_context_create($opts);
        // construction de l'url fitnet
        $base = Configure::read('fitnet.base');
        if (substr($url, 0, 1) == "/" ) {
            $url = substr($url, 1);
        }
        $url=$base . $url ;
        // appel de la requête
        $result = @file_get_contents($url, false, $context);
        if($result === FALSE){
            $result = 'error';
        }

        // résultat
        return $result;
    }

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('RequestHandler');
        $this->loadComponent('Flash');

        /*
         * Enable the following components for recommended CakePHP security settings.
         * see https://book.cakephp.org/3.0/en/controllers/components/security.html
         */
        //$this->loadComponent('Security');
        //$this->loadComponent('Csrf');

        // $this->viewBuilder()->layout('frontend'); //modifier template de base

        $this->loadComponent('Auth', [
            'authorize'=> 'Controller',
            'authenticate' => [
                'Form' => [
                    'fields' => [
                        'username' => 'email',
                        'password' => 'mdp'
                    ]
                ]
            ],
            'loginAction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
             // Si pas autorisé, on renvoit sur la page précédente
            'unauthorizedRedirect' => $this->referer()
        ]);

        // Permet à l'action "display" de notre PagesController de continuer
        // à fonctionner. Autorise également les actions "read-only".
        $this->Auth->allow(['display','login', 'cksession']);
    }

    public function isAuthorized($user)
    {
        // Par défaut, on refuse l'accès.
        return false;
    }
}
