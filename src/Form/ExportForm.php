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
            ->addField('date_fin', ['type' => 'date'])
            ->addField('fitnet', ['type' => 'int']);
        return $schema;
    }

    protected function _buildValidator(Validator $validator)
    {

        $validator->requirePresence('date_debut', 'create');
        $validator->requirePresence('date_fin', 'create');
        $validator->add('date_fin', [
            'supToDebut' => [
                'rule' => function ($value, $context) {
                    return $value >= $context['data']['date_debut'];
                },
                'message' => __("Date de fin inférieur à celle de début.")
            ]
        ]);
        return $validator;
    }

    protected function _execute(array $data)
    {
        // Envoie un email.
        return true;
    }
}
