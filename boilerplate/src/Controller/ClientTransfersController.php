<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Client Transfers Controller
 *
 * Handles wallet-to-wallet transfers for regular users
 * Simplified interface compared to admin TransfersController
 */
class ClientTransfersController extends AppController
{
    /**
     * Transfer form and process transfers for clients
     *
     * @return \Cake\Http\Response|null
     */
    public function send()
    {
        // Load required models
        $this->loadModel('Wallets');
        $this->loadModel('Transactions');

        // Get current user's wallet
        $currentUserId = $this->Auth->user('id');
        $senderWallet = $this->Wallets->find()
            ->where(['user_id' => $currentUserId])
            ->first();

        if (!$senderWallet) {
            $this->Flash->error('You do not have a wallet. Please contact support.');
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            // Validate input data
            $validationResult = $this->validateTransferData($data, $senderWallet);
            if ($validationResult !== true) {
                $this->Flash->error($validationResult);
                $this->set(compact('senderWallet'));
                return;
            }

            // Get recipient wallet by address
            $recipientWallet = $this->Wallets->find()
                ->where(['address' => $data['recipient_wallet_address']])
                ->first();

            // Process the transfer
            $transferResult = $this->processTransfer($senderWallet, $recipientWallet, $data);

            if ($transferResult === true) {
                $this->Flash->success('Transfer completed successfully! Money has been sent.');
                return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
            } else {
                $this->Flash->error($transferResult);
            }
        }

        $this->set(compact('senderWallet'));
    }

    /**
     * Validate transfer data
     *
     * @param array $data Transfer data
     * @param \App\Model\Entity\Wallet $senderWallet Sender's wallet
     * @return string|true Error message or true if valid
     */
    private function validateTransferData($data, $senderWallet)
    {
        // Check if required fields are present
        if (empty($data['recipient_wallet_address']) || !isset($data['amount']) || $data['amount'] === '') {
            return 'Please fill in all required fields';
        }

        // Validate amount
        $amount = floatval($data['amount']);
        if ($amount <= 0) {
            return 'Transfer amount must be positive';
        }

        // Check if sender has sufficient balance
        if ($senderWallet->balance < $amount) {
            return 'Insufficient balance for this transfer';
        }

        // Check if recipient wallet exists by address
        $recipientWallet = $this->Wallets->find()
            ->where(['address' => $data['recipient_wallet_address']])
            ->first();

        if (!$recipientWallet) {
            return 'Recipient wallet address not found';
        }

        // Check if not transferring to own wallet
        if ($recipientWallet->user_id == $senderWallet->user_id) {
            return 'Cannot transfer to your own wallet';
        }

        return true;
    }

    /**
     * Process the transfer between wallets
     *
     * @param \App\Model\Entity\Wallet $senderWallet Sender's wallet
     * @param \App\Model\Entity\Wallet $recipientWallet Recipient's wallet
     * @param array $data Transfer data
     * @return string|true Error message or true if successful
     */
    private function processTransfer($senderWallet, $recipientWallet, $data)
    {
        $amount = floatval($data['amount']);
        $description = !empty($data['description']) ? $data['description'] : 'Money transfer';

        // Use database transaction for atomicity
        $connection = ConnectionManager::get('default');

        try {
            $connection->begin();

            // Update sender wallet balance
            $senderWallet->balance -= $amount;
            if (!$this->Wallets->save($senderWallet)) {
                throw new \Exception('Failed to update sender wallet');
            }

            // Update recipient wallet balance
            $recipientWallet->balance += $amount;
            if (!$this->Wallets->save($recipientWallet)) {
                throw new \Exception('Failed to update recipient wallet');
            }

            // Create debit transaction for sender
            $debitTransaction = $this->Transactions->newEntity([
                'wallet_id' => $senderWallet->id,
                'type' => 'transfer_out',
                'amount' => -$amount, // Negative amount for debit
                'description' => $description . ' (sent to ' . substr($recipientWallet->address, 0, 10) . '...)',
                'recipient_wallet_id' => $recipientWallet->id
            ]);

            if (!$this->Transactions->save($debitTransaction)) {
                throw new \Exception('Failed to create debit transaction');
            }

            // Create credit transaction for recipient
            $creditTransaction = $this->Transactions->newEntity([
                'wallet_id' => $recipientWallet->id,
                'type' => 'transfer_in',
                'amount' => $amount, // Positive amount for credit
                'description' => $description . ' (received from ' . substr($senderWallet->address, 0, 10) . '...)',
                'recipient_wallet_id' => $senderWallet->id,
                'related_transaction_id' => $debitTransaction->id
            ]);

            if (!$this->Transactions->save($creditTransaction)) {
                throw new \Exception('Failed to create credit transaction');
            }

            // Update debit transaction with related transaction ID
            $debitTransaction->related_transaction_id = $creditTransaction->id;
            if (!$this->Transactions->save($debitTransaction)) {
                throw new \Exception('Failed to link transactions');
            }

            $connection->commit();
            return true;

        } catch (\Exception $e) {
            $connection->rollback();
            return 'Transfer failed: ' . $e->getMessage();
        }
    }
}
