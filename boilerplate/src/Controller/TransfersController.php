<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;

/**
 * Transfers Controller
 *
 * Handles internal wallet-to-wallet transfers
 */
class TransfersController extends AppController
{
    /**
     * Create transfer form and process transfers
     *
     * @return \Cake\Http\Response|null
     */
    public function create()
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
            return $this->redirect(['controller' => 'Wallets', 'action' => 'index']);
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
                $this->Flash->success('Transfer completed successfully');
                return $this->redirect(['controller' => 'Wallets', 'action' => 'index']);
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
        $description = !empty($data['description']) ? $data['description'] : 'Internal transfer';

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
                'description' => $description . ' (to wallet #' . $recipientWallet->id . ')',
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
                'description' => $description . ' (from wallet #' . $senderWallet->id . ')',
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

    /**
     * List available wallets for transfer (AJAX endpoint)
     *
     * @return \Cake\Http\Response|null
     */
    public function getWallets()
    {
        $this->request->allowMethod(['get']);

        $currentUserId = $this->Auth->user('id');

        // Get all wallets except current user's wallet
        $wallets = $this->Wallets->find()
            ->contain(['Users'])
            ->where(['Wallets.user_id !=' => $currentUserId])
            ->toArray();

        $this->set(compact('wallets'));
        $this->viewBuilder()->setOption('serialize', ['wallets']);
    }
}
