<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class LoansTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('loans');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
        ]);
        $this->hasMany('Investments', [
            'foreignKey' => 'loan_id',
        ]);
    }

    public function getTotalInvested($loanId)
    {
        $investmentsTable = TableRegistry::getTableLocator()->get('Investments');
        $total = $investmentsTable->find()
            ->where(['loan_id' => $loanId])
            ->sumOf('amount');
        return $total;
    }

    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id');

        $validator
            ->scalar('project_name')
            ->maxLength('project_name', 255)
            ->requirePresence('project_name', 'create')
            ->notEmptyString('project_name');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->numeric('amount')
            ->greaterThan('amount', 0, 'Amount must be positive')
            ->lessThanOrEqual('amount', 100000, 'Amount exceeds limit')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->numeric('interest_rate')
            ->greaterThanOrEqual('interest_rate', 0, 'Interest must be positive')
            ->lessThanOrEqual('interest_rate', 100, 'Interest rate too high')
            ->requirePresence('interest_rate', 'create')
            ->notEmptyString('interest_rate');

        $validator
            ->numeric('loan_limit')
            ->greaterThanOrEqual('loan_limit', 0)
            ->lessThanOrEqual('loan_limit', 1000000, 'Loan limit too large')
            ->allowEmptyString('loan_limit');

        $validator
            ->scalar('status')
            ->maxLength('status', 30)
            ->requirePresence('status', 'create')
            ->notEmptyString('status');

        $validator
            ->numeric('income')
            ->greaterThanOrEqual('income', 0)
            ->lessThanOrEqual('income', 1000000, 'Income too large')
            ->allowEmptyString('income');

        $validator
            ->integer('credit_score')
            ->greaterThanOrEqual('credit_score', 300, 'Minimum credit score is 300')
            ->lessThanOrEqual('credit_score', 850, 'Maximum credit score is 850')
            ->allowEmptyString('credit_score');

        $validator
            ->dateTime('created')
            ->allowEmptyDateTime('created');

        return $validator;
    }
}
