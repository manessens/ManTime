<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Profil Model
 *
 * @property \App\Model\Table\LignMatTable|\Cake\ORM\Association\HasMany $LignMat
 *
 * @method \App\Model\Entity\Profil get($primaryKey, $options = [])
 * @method \App\Model\Entity\Profil newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Profil[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Profil|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Profil patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Profil[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Profil findOrCreate($search, callable $callback = null, $options = [])
 */
class ProfilTable extends Table
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

        $this->setTable('profil');
        $this->setDisplayField('id_profil');
        $this->setPrimaryKey('id_profil');

        $this->hasMany('LignMat', [
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
            ->integer('id_profil')
            ->allowEmpty('id_profil', 'create');

        $validator
            ->scalar('nom_profil')
            ->maxLength('nom_profil', 50)
            ->requirePresence('nom_profil', 'create')
            ->notEmpty('nom_profil');

        return $validator;
    }
    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->isUnique(['nom_profil']));

        return $rules;
    }
}
