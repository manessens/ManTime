<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class AuthfitForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        $schema
            ->addField('loginFit', ['type' => 'string'])
            ->addField('passwordFit', ['type' => 'string']);
        return $schema;
    }

    protected function _buildValidator(Validator $validator)
    {
        $validator->requirePresence('loginFit', 'create');
        $validator->requirePresence('passwordFit', 'create');
        return $validator;
    }

    protected function _execute(array $data)
    {
        // Envoie un email.
        return true;
    }
}
