<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Matrice Model
 *
 * @method \App\Model\Entity\Matrice get($primaryKey, $options = [])
 * @method \App\Model\Entity\Matrice newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Matrice[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Matrice|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Matrice patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Matrice[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Matrice findOrCreate($search, callable $callback = null, $options = [])
 */
class MatriceTable extends Table
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

        $this->setTable('matrice');
        $this->setDisplayField('idm');
        $this->setPrimaryKey('idm');
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
            ->integer('idm')
            ->allowEmpty('idm', 'create');

        $validator
            ->scalar('nom_matrice')
            ->maxLength('nom_matrice', 50)
            ->requirePresence('nom_matrice', 'create')
            ->notEmpty('nom_matrice');

        return $validator;
    }
}
