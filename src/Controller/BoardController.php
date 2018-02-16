<?php

use Cake\Error\Debugger;

namespace App\Controller;

class ArticlesController extends AppController
{
    public function index()
    {
        $this->set(compact('user'));
    }

}
