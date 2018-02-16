<?php

use Cake\Error\Debugger;

namespace App\Controller;

class BoardController extends AppController
{
    public function index()
    {
        $this->set(compact('user'));
    }

}
