<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Participant Model
 *
 * @property \App\Model\Entity\User|\Cake\ORM\Association\BelongsTo $Users
 * @property \App\Model\Entity\Projet|\Cake\ORM\Association\BelongsTo $Projet
 *
 *
 * @method \App\Model\Entity\Participant get($primaryKey, $options = [])
 * @method \App\Model\Entity\Participant newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Participant[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Participant|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Participant patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Participant[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Participant findOrCreate($search, callable $callback = null, $options = [])
 */
class ParticipantTable extends Table
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

        $this->setTable('participant');
        $this->setDisplayField('idu');
        $this->setPrimaryKey(['idu', 'idp']);

        $this->belongsTo('Users', [
            'foreignKey' => 'idu'
        ]);
        $this->belongsTo('Projet', [
            'foreignKey' => 'idp'
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
            ->integer('idu')
            ->allowEmpty('idu', 'create');

        $validator
            ->integer('idp')
            ->allowEmpty('idp', 'create');

        return $validator;
    }
}
