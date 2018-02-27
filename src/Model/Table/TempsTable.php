<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Temps Model
 *
 * @method \App\Model\Entity\Temp get($primaryKey, $options = [])
 * @method \App\Model\Entity\Temp newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Temp[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Temp|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Temp patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Temp[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Temp findOrCreate($search, callable $callback = null, $options = [])
 */
class TempsTable extends Table
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

        $this->setTable('temps');
        $this->setDisplayField('idt');
        $this->setPrimaryKey('idt');
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
            ->integer('idt')
            ->allowEmpty('idt', 'create');

        $validator
            ->dateTime('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        $validator
            ->decimal('time')
            ->allowEmpty('time');

        $validator
            ->integer('n_ligne')
            ->requirePresence('n_ligne', 'create')
            ->notEmpty('n_ligne');

        $validator
            ->integer('idu')
            ->requirePresence('idu', 'create')
            ->notEmpty('idu');

        $validator
            ->integer('idp')
            ->requirePresence('idp', 'create')
            ->notEmpty('idp');

        $validator
            ->integer('id_profil')
            ->requirePresence('id_profil', 'create')
            ->notEmpty('id_profil');

        $validator
            ->integer('ida')
            ->requirePresence('ida', 'create')
            ->notEmpty('ida');

        return $validator;
    }
}
