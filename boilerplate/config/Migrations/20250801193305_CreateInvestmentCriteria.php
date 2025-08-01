<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateInvestmentCriteria extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('investment_criteria');
        $table
            ->addColumn('user_id', 'integer', ['null' => false, 'signed' => false])
            ->addColumn('min_credit_score', 'integer', ['null' => true, 'default' => null])
            ->addColumn('min_loan_amount', 'float', ['precision' => 15, 'scale' => 2, 'null' => true, 'default' => null])
            ->addColumn('max_loan_amount', 'float', ['precision' => 15, 'scale' => 2, 'null' => true, 'default' => null])
            ->addColumn('risk_tolerance', 'string', ['limit' => 10, 'null' => true, 'default' => null])
            ->addColumn('loan_purpose', 'string', ['limit' => 100, 'null' => true, 'default' => null])
            ->addColumn('require_collateral', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('require_real_estate', 'boolean', ['null' => true, 'default' => null])
            ->addColumn('preferred_region', 'string', ['limit' => 255, 'null' => true, 'default' => null])
            ->addColumn('business_or_individual', 'string', ['limit' => 30, 'null' => true, 'default' => null])
            ->addColumn('created', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => true])
            ->addColumn('modified', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'null' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
            ->create();
    }
}
