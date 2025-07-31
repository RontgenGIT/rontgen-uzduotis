<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @property \App\Model\Table\WalletsTable&\Cake\ORM\Association\HasMany $Wallets
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
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
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Wallets', [
            'foreignKey' => 'user_id',
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->email('email')
            ->notEmptyString('email', 'Please enter an email')
            ->add('email', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $this->passwordValidation($validator);

        $validator
            ->scalar('role')
            ->maxLength('role', 20)
            ->inList('role', ['user', 'admin'], 'Role must be either user or admin')
            ->notEmptyString('role');

        return $validator;
    }

    public function validationRegister(Validator $validator)
    {
        $validator = $this->validationDefault($validator);
        $this->passwordValidation($validator);

        $validator
            ->requirePresence('confirm_password', 'create')
            ->notEmptyString('confirm_password', 'Please confirm your password')
            ->add('confirm_password', 'matchPassword', [
                'rule' => function ($value, $context) {
                    return isset($context['data']['password']) && $value === $context['data']['password'];
                },
                'message' => 'Passwords do not match',
            ]);

        return $validator;
    }

    private function passwordValidation(Validator $validator)
    {
        return $validator
            ->scalar('password')
            ->notEmptyString('password', 'Password is required')
            ->maxLength('password', 255, 'Password is too long')
            ->minLength('password', 8, 'Password must be at least 8 characters long')
            ->regex('password', '/[0-9]/', 'Password must contain at least one number')
            ->regex('password', '/[^A-Za-z0-9]/', 'Password must contain at least one special character');
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
