<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Projet Model
 *
 * @property \App\Model\Entity\Client|\Cake\ORM\Association\BelongsTo $Client
 * @property \App\Model\Entity\Facturable|\Cake\ORM\Association\BelongsTo $Facturable
 * @property \App\Model\Table\ActivitiesTable|\Cake\ORM\Association\HasMany $Activity
 * @property \App\Model\Table\ParticipantTable|\Cake\ORM\Association\HasMany $Participant
 *
 *
 * @method \App\Model\Entity\Projet get($primaryKey, $options = [])
 * @method \App\Model\Entity\Projet newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Projet[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Projet|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Projet patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Projet[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Projet findOrCreate($search, callable $callback = null, $options = [])
 */
class ProjetTable extends Table
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

        $this->setTable('projet');
        $this->setDisplayField('idp');
        $this->setPrimaryKey('idp');

        $this->belongsTo('Client', [
            'foreignKey' => 'idc'
        ]);
        $this->belongsTo('Facturable', [
            'foreignKey' => 'idf'
        ]);

        $this->hasMany('Activities', [
            'foreignKey' => 'idp'
        ]);
        $this->hasMany('Participant', [
            'foreignKey' => 'idp'
        ]);
        $this->belongsTo('Matrice', [
            'foreignKey' => 'idm'
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
            ->integer('idp')
            ->allowEmpty('idp', 'create');

        $validator
            ->integer('id_fit')
            ->allowEmpty('id_fit');

        $validator
            ->scalar('nom_projet')
            ->maxLength('nom_projet', 50)
            ->requirePresence('nom_projet', 'create')
            ->notEmpty('nom_projet');

        $validator
            ->integer('idc')
            ->requirePresence('idc', 'create')
            ->notEmpty('idc');

        $validator
            ->integer('idf')
            ->requirePresence('idf', 'create')
            ->notEmpty('idf');

        $validator
            ->dateTime('date_debut')
            ->requirePresence('date_debut', 'create')
            ->notEmpty('date_debut');

        $validator
            ->dateTime('date_fin')
            ->requirePresence('date_fin', 'create')
            ->notEmpty('date_fin');

        $validator
            ->integer('idm')
            ->allowEmpty('idm');

        $validator
            ->decimal('prix')
            ->requirePresence('prix', 'create')
            ->notEmpty('prix');

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
}
