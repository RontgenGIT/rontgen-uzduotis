<?php

use Migrations\AbstractSeed;
use Cake\ORM\TableRegistry;

/**
 * Transactions seed.
 */
class CTransactionsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
        $walletsTable = TableRegistry::getTableLocator()->get('Wallets');

        // Get existing wallets to create transactions for
        $wallets = $walletsTable->find()->all();

        if ($wallets->isEmpty()) {
            echo "No wallets found. Please run UsersSeed and WalletsSeed first.\n";
            return;
        }

        // Clear existing transactions to avoid duplicates (safer method for SQLite)
        $existingTransactions = $transactionsTable->find()->all();
        foreach ($existingTransactions as $transaction) {
            $transactionsTable->delete($transaction, ['atomic' => false]);
        }

        $transactions = [];

        $walletArray = $wallets->toArray();

        foreach ($walletArray as $index => $wallet) {
            // Create some initial balance (simulating previous deposits)
            $transactions[] = [
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => 500.00,
                'description' => 'Initial wallet funding',
                'created' => date('Y-m-d H:i:s', strtotime('-30 days'))
            ];

            // Create internal transfers between wallets (if we have multiple wallets)
            if (count($walletArray) > 1) {
                $otherWallets = array_filter($walletArray, function($w) use ($wallet) {
                    return $w->id !== $wallet->id;
                });

                if (!empty($otherWallets)) {
                    $recipientWallet = array_values($otherWallets)[0];

                    // Create transfer out transaction
                    $transactions[] = [
                        'wallet_id' => $wallet->id,
                        'type' => 'transfer_out',
                        'amount' => -50.00,
                        'description' => 'Internal transfer (to wallet #' . $recipientWallet->id . ')',
                        'recipient_wallet_id' => $recipientWallet->id,
                        'created' => date('Y-m-d H:i:s', strtotime('-15 days'))
                    ];

                    // Create corresponding transfer in transaction
                    $transactions[] = [
                        'wallet_id' => $recipientWallet->id,
                        'type' => 'transfer_in',
                        'amount' => 50.00,
                        'description' => 'Internal transfer (from wallet #' . $wallet->id . ')',
                        'recipient_wallet_id' => $wallet->id,
                        'created' => date('Y-m-d H:i:s', strtotime('-15 days'))
                    ];
                }
            }

            // Create some recent deposit
            $transactions[] = [
                'wallet_id' => $wallet->id,
                'type' => 'deposit',
                'amount' => 100.00,
                'description' => 'Recent wallet top-up',
                'created' => date('Y-m-d H:i:s', strtotime('-3 days'))
            ];
        }

        // Save all transactions
        foreach ($transactions as $transactionData) {
            $transaction = $transactionsTable->newEntity($transactionData);
            if (!$transactionsTable->save($transaction, ['atomic' => false])) {
                echo "Failed to save transaction: " . print_r($transaction->getErrors(), true) . "\n";
            }
        }

        echo "Transactions seeded successfully. Created " . count($transactions) . " transactions.\n";
    }
}
