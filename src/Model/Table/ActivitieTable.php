<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Activitie Model
 *
 * @method \App\Model\Entity\Activitie get($primaryKey, $options = [])
 * @method \App\Model\Entity\Activitie newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Activitie[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Activitie|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Activitie patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Activitie[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Activitie findOrCreate($search, callable $callback = null, $options = [])
 */
class ActivitieTable extends Table
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

        $this->setTable('activitie');
        $this->setDisplayField('ida');
        $this->setPrimaryKey('ida');
        $this->setDisplayField('nom_activit');
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
            ->integer('ida')
            ->allowEmpty('ida', 'create');

        $validator
            ->scalar('nom_activit')
            ->maxLength('nom_activit', 50)
            ->requirePresence('nom_activit', 'create')
            ->notEmpty('nom_activit');

        return $validator;
    }
}
