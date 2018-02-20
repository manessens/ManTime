<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * LignMat Model
 *
 * @property \App\Model\Entity\ProfilTable|\Cake\ORM\Association\BelongsTo $Profil
 *
 * @method \App\Model\Entity\LignMat get($primaryKey, $options = [])
 * @method \App\Model\Entity\LignMat newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\LignMat[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\LignMat|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\LignMat patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\LignMat[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\LignMat findOrCreate($search, callable $callback = null, $options = [])
 */
class LignMatTable extends Table
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

        $this->setTable('lign_mat');
        $this->setDisplayField('id_ligne');
        $this->setPrimaryKey('id_ligne');

        $this->belongsTo('Profil', [
            'foreignKey' => 'id_profil'
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
            ->integer('id_ligne')
            ->allowEmpty('id_ligne', 'create');

        $validator
            ->integer('idm')
            ->requirePresence('idm', 'create')
            ->notEmpty('idm');

        $validator
            ->integer('id_profil')
            ->requirePresence('id_profil', 'create')
            ->notEmpty('id_profil');

        $validator
            ->decimal('heur')
            ->requirePresence('heur', 'create')
            ->notEmpty('heur');

        $validator
            ->decimal('jour')
            ->requirePresence('jour', 'create')
            ->notEmpty('jour');

        return $validator;
    }
}
