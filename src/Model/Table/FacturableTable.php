<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Facturable Model
 *
 * @method \App\Model\Entity\Facturable get($primaryKey, $options = [])
 * @method \App\Model\Entity\Facturable newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Facturable[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Facturable|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Facturable patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Facturable[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Facturable findOrCreate($search, callable $callback = null, $options = [])
 */
class FacturableTable extends Table
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

        $this->setTable('facturable');
        $this->setDisplayField('nom_fact');
        $this->setPrimaryKey('idf');
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
            ->integer('idf')
            ->allowEmpty('idf', 'create');

        $validator
            ->integer('id_fit')
            ->allowEmpty('id_fit', 'create');

        $validator
            ->scalar('nom_fact')
            ->maxLength('nom_fact', 20)
            ->requirePresence('nom_fact', 'create')
            ->notEmpty('nom_fact');

        return $validator;
    }
}
