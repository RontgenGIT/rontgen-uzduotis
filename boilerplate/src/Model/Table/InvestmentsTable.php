<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;

class InvestmentsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->addBehavior('Timestamp');
        $this->setTable('investments');
        $this->setPrimaryKey('id');

        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->belongsTo('Loans', [
            'foreignKey' => 'loan_id',
        ]);
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create')

            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id')

            ->integer('loan_id')
            ->requirePresence('loan_id', 'create')
            ->notEmptyString('loan_id')

            ->numeric('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount')

            ->scalar('status')
            ->maxLength('status', 30)
            ->requirePresence('status', 'create')
            ->notEmptyString('status')

            ->dateTime('created')
            ->allowEmptyDateTime('created');

        return $validator;
    }
}
