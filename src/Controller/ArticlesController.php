<?php
// src/Controller/ArticlesController.php

use Cake\Error\Debugger;

namespace App\Controller;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->loadComponent('Paginator');
        $articles = $this->Paginator->paginate($this->Articles->find());
        $this->set(compact('articles'));
        $this->set('controller', 'Articles');
    }

    public function view($ref = null)
    {
        $article = $this->Articles->findByRef($ref)->firstOrFail();
        $this->set(compact('article'));
    }

    public function add()
    {
        $article = $this->Articles->newEntity();
        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            // Hardcoding the user_id is temporary, and will be removed later
            // when we build authentication out.
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Votre article a été sauvegardé.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter votre article.'));
        }
        $this->set('article', $article);

    }

    public function edit($ref)
    {
        $article = $this->Articles->findByRef($ref)->firstOrFail();
        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData());
            if ($this->Articles->save($article)) {
                $this->Flash->success(__('Votre article a été mis à jour.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible de mettre à jour l\'article.'));
        }

        $this->set('article', $article);
    }

    public function delete($ref)
    {
        $this->request->allowMethod(['post', 'delete']);

        $article = $this->Articles->findByRef($ref)->firstOrFail();
        if ($this->Articles->delete($article)) {
            $this->Flash->success(__('L\'article {0} a été supprimé.', $article->title));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');
        // Les actions 'add' et 'tags' sont toujours autorisés pour les utilisateur
        // authentifiés sur l'application
        if (in_array($action, ['add', 'tags']) ) {
            return true;
        }

        // Toutes les autres actions nécessitent un slug
        $ref = $this->request->getParam('pass.0');
        if (!$ref) {
            return false;
        }

        // On vérifie que l'article appartient à l'utilisateur connecté
        $article = $this->Articles->findByRef($ref)->first();

        return $article->user_id === $user['id'];
    }

}
