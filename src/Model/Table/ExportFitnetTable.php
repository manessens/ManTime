<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ExportFitnet Model
 *
 * @property \App\Model\Entity\Client|\Cake\ORM\Association\BelongsTo $Client
 * @property \App\Model\Entity\User|\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \App\Model\Entity\ExportFitnet get($primaryKey, $options = [])
 * @method \App\Model\Entity\ExportFitnet newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ExportFitnet[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ExportFitnet|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ExportFitnet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ExportFitnet[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ExportFitnet findOrCreate($search, callable $callback = null, $options = [])
 */
class ExportFitnetTable extends Table
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

        $this->setTable('export_fitnet');
        $this->setDisplayField('id_fit');
        $this->setPrimaryKey('id_fit');

        $this->belongsTo('Client', [
            'foreignKey' => 'idc'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'idu'
        ]);
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
            ->integer('id_fit')
            ->allowEmpty('id_fit', 'create');

        $validator
            ->dateTime('date_debut')
            ->requirePresence('date_debut', 'create')
            ->notEmpty('date_debut');

        $validator
            ->dateTime('date_fin')
            ->requirePresence('date_fin', 'create')
            ->notEmpty('date_fin');

        $validator
            ->integer('idc')
            ->allowEmpty('idc');

        $validator
            ->integer('idu')
            ->allowEmpty('idu');

        $validator
            ->scalar('etat')
            ->maxLength('etat', 10)
            ->requirePresence('etat', 'create')
            ->notEmpty('etat');
        //
        // $validator
        //     ->dateTime('date_create')
        //     ->requirePresence('date_create', 'create')
        //     ->notEmpty('date_create');

        return $validator;
    }
}
