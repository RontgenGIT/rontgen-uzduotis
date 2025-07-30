<?php
use Migrations\AbstractMigration;

class CreateUsersWalletsAndTransactions extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        // Users table
        $users = $this->table('users');
        $users->addColumn('email', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('password', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('created', 'datetime')
              ->addColumn('modified', 'datetime')
              ->addIndex(['email'], ['unique' => true])
              ->create();

        // Wallets table
        $wallets = $this->table('wallets');
        $wallets->addColumn('user_id', 'integer', ['signed' => 'disable'])
                ->addColumn('balance', 'float', ['precision' => 10, 'scale' => 2, 'default' => 0.00])
                ->addColumn('currency', 'string', ['limit' => 3, 'default' => 'EUR'])
                ->addColumn('created', 'datetime')
                ->addColumn('modified', 'datetime')
                ->addForeignKey('user_id', 'users', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
                ->create();

        // Transactions table
        $transactions = $this->table('transactions');
        $transactions->addColumn('wallet_id', 'integer', ['signed' => 'disable'])
                     ->addColumn('type', 'string', ['limit' => 20, 'null' => false])
                     ->addColumn('amount', 'float', ['precision' => 10, 'scale' => 2])
                     ->addColumn('description', 'string', ['limit' => 255, 'null' => true])
                     ->addColumn('created', 'datetime')
                     ->addForeignKey('wallet_id', 'wallets', 'id', ['delete'=> 'CASCADE', 'update'=> 'NO_ACTION'])
                     ->create();
    }
}
