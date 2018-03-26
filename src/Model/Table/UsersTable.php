<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Auth\DefaultPasswordHasher;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('idu');
        $this->setPrimaryKey('idu');

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
            ->scalar('prenom')
            ->maxLength('prenom', 50)
            ->allowEmpty('prenom');

        $validator
            ->scalar('nom')
            ->maxLength('nom', 50)
            ->allowEmpty('nom');

        $validator
            ->email('email')
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->scalar('mdp')
            ->add('mdp', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'Le mot de pass doit avoir au moins 6 caractères',
                ]
            ])
            ->requirePresence('mdp', 'create')
            ->notEmpty('mdp');

        $validator
            ->integer('actif')
            ->allowEmpty('actif');

        $validator
            ->integer('prem_connect')
            ->allowEmpty('prem_connect');

        $validator
            ->integer('admin')
            ->allowEmpty('admin');

        $validator
            ->scalar('password2')
            ->add('password2', [
                'length' => [
                    'rule' => ['minLength', 6],
                    'message' => 'Le mot de pass doit avoir au moins 6 caractères',
                ]
            ])
            ->add('password2',[
                'match'=>[
                    'rule'=> ['compareWith','mdp'],
                    'message'=>'Les mot de pass sont différents.',
                ]
            ])
            ->notEmpty('password2');


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
        $rules->add($rules->isUnique(['email']));

        return $rules;
    }

}
