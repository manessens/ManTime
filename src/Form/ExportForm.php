<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class ExportForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('client', 'int')
            ->addField('user', ['type' => 'int'])
            ->addField('date_debut', ['type' => 'date'])
            ->addField('date_fin', ['type' => 'date']);
        return $schema;
    }

    protected function _buildValidator(Validator $validator)
    {
        // $validator->add('name', 'length', [
        //         'rule' => ['minLength', 10],
        //         'message' => 'Un nom est requis'
        //     ])->add('email', 'format', [
        //         'rule' => 'email',
        //         'message' => 'Une adresse email valide est requise',
        //     ]);
        return $validator;
    }

    protected function _execute(array $data)
    {
        // Envoie un email.
        return true;
    }
}
