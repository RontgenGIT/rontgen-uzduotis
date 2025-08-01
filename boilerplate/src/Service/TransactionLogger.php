<?php

namespace App\Service;

use Cake\ORM\TableRegistry;

/**
 * TransactionLogger service
 * Usage:
 *   use App\Service\TransactionLogger;
 *   TransactionLogger::record($walletId, $amount, $type, $relatedId, $description);
 */
class TransactionLogger
{
    /**
     * Log a transaction event for a wallet.
     *
     * @param int $walletId       The wallet ID involved in the transaction.
     * @param float $amount       The amount of the transaction (+ for credit, - for debit).
     * @param string $type        Transaction type (e.g. 'invest', 'receive', 'repay', 'refund', 'deposit', 'withdraw').
     * @param int|null $relatedId Optional related entity ID (e.g. loan_id, investment_id).
     * @param string $description Optional description for the transaction.
     * @return void
     */

    public static function record($walletId, $amount, $type, $relatedId = null, $description = '')
    {
        $transactionsTable = TableRegistry::getTableLocator()->get('Transactions');
        $transactionData = [
            'wallet_id'   => $walletId,
            'amount'      => $amount,
            'type'        => $type,
            'related_id'  => $relatedId,
            'description' => $description,
            'created'     => date('Y-m-d H:i:s')
        ];
        $transaction = $transactionsTable->newEntity($transactionData);
        $transactionsTable->save($transaction);
    }
}
