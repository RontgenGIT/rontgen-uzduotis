<?php
use Migrations\AbstractSeed;
use Cake\ORM\TableRegistry;

class WalletsSeed extends AbstractSeed
{
    public function run()
    {
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        $walletsTable = TableRegistry::getTableLocator()->get('Wallets');

        // Clear existing wallets (safer method for SQLite)
        $existingWallets = $walletsTable->find()->all();
        foreach ($existingWallets as $wallet) {
            $walletsTable->delete($wallet, ['atomic' => false]);
        }

        // Get all users
        $users = $usersTable->find()->all();

        foreach ($users as $user) {
            $wallet = $walletsTable->newEntity([
                'user_id' => $user->id,
                'balance' => 100.00, // Give each user some starting balance
                'currency' => 'EUR'
            ]);

            if ($walletsTable->save($wallet, ['atomic' => false])) {
                echo "Wallet created for user {$user->email} with address {$wallet->address}\n";
            } else {
                echo "Failed to create wallet for user {$user->email}\n";
                print_r($wallet->getErrors());
            }
        }
    }
}
