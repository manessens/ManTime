<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Origine Model
 *
 * @method \App\Model\Entity\Origine get($primaryKey, $options = [])
 * @method \App\Model\Entity\Origine newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Origine[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Origine|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Origine patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Origine[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Origine findOrCreate($search, callable $callback = null, $options = [])
 */
class OrigineTable extends Table
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

        $this->setTable('origine');
        $this->setDisplayField('nom_origine');
        $this->setPrimaryKey('ido');
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
            ->integer('ido')
            ->allowEmpty('ido', 'create');

        $validator
            ->integer('id_fit')
            ->allowEmpty('id_fit', 'create');

        $validator
            ->scalar('nom_origine')
            ->maxLength('nom_origine', 20)
            ->requirePresence('nom_origine', 'create')
            ->notEmpty('nom_origine');

        return $validator;
    }
}
