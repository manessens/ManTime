<?php
namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class ImportForm extends Form
{

    protected function _buildSchema(Schema $schema)
    {
        $schema->addField('fileimport', 'file');
        return $schema;
    }

    protected function _buildValidator(Validator $validator)
    {

        $validator->requirePresence('fileimport', 'create');
        return $validator;
    }

    protected function _execute(array $data)
    {
        // Envoie un email.
        return true;
    }
}
