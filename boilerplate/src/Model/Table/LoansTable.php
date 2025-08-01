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
            ->allowEmptyString('id', null, 'create')

            ->integer('user_id')
            ->requirePresence('user_id', 'create')
            ->notEmptyString('user_id')

            ->scalar('project_name')
            ->maxLength('project_name', 255)
            ->requirePresence('project_name', 'create')
            ->notEmptyString('project_name')

            ->scalar('description')
            ->allowEmptyString('description')

            ->numeric('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount')

            ->numeric('interest_rate')
            ->requirePresence('interest_rate', 'create')
            ->notEmptyString('interest_rate')

            ->numeric('loan_limit')
            ->allowEmptyString('loan_limit')

            ->scalar('status')
            ->maxLength('status', 30)
            ->requirePresence('status', 'create')
            ->notEmptyString('status')

            ->numeric('income')
            ->allowEmptyString('income')

            ->integer('credit_score')
            ->allowEmptyString('credit_score')

            ->dateTime('created')
            ->allowEmptyDateTime('created');

        return $validator;
    }
}
