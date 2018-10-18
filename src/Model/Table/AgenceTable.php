<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agence Model
 *
 * @method \App\Model\Entity\Agence get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agence newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agence[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agence|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agence patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agence[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agence findOrCreate($search, callable $callback = null, $options = [])
 */
class AgenceTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('agence');
        $this->setPrimaryKey('id_agence');
        $this->setDisplayField('nom_agence');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id_agence')
            ->allowEmpty('id_agence', 'create');

        $validator
            ->integer('id_fit')
            ->allowEmpty('id_fit', 'create');

        $validator
            ->scalar('nom_agence')
            ->maxLength('nom_agence', 20)
            ->requirePresence('nom_agence', 'create')
            ->notEmpty('nom_agence');

        return $validator;
    }
}
