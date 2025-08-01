<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateLendingSystem extends AbstractMigration
{
    public function change()
    {
        $loans = $this->table('loans');
        $loans->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('project_name', 'string', ['limit' => 255, 'null' => false])
            ->addColumn('description', 'text', ['null' => true])
            ->addColumn('amount', 'float', ['null' => false])
            ->addColumn('interest_rate', 'float', ['null' => false])
            ->addColumn('status', 'string', ['limit' => 30, 'null' => false])
            ->addColumn('income', 'float', ['null' => true])
            ->addColumn('credit_score', 'integer', ['null' => true])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addColumn('return_date', 'datetime', ['null' => true])
            ->addColumn('repaid', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('collateral', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('country', 'string', ['limit' => 100, 'null' => true])
            ->addColumn('risk', 'integer', ['limit' => 20, 'null' => true])
            ->addColumn('real_estate', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('business_or_individual', 'string', ['limit' => 30, 'null' => true])
            ->addColumn('purpose', 'string', ['limit' => 100, 'null' => true])
            ->addIndex(['user_id'])
            ->create();

        $investments = $this->table('investments');
        $investments->addColumn('user_id', 'integer', ['null' => false])
            ->addColumn('loan_id', 'integer', ['null' => false])
            ->addColumn('amount', 'float', ['null' => false])
            ->addColumn('status', 'string', ['limit' => 30, 'null' => false])
            ->addColumn('created', 'datetime', ['null' => false])
            ->addIndex(['user_id'])
            ->addIndex(['loan_id'])
            ->addForeignKey('loan_id', 'loans', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
