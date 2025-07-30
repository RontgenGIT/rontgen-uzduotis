<?php
namespace App\Controller;

use Cake\Event\Event;

/**
 * Client Payments Controller
 *
 * Handles Paysera payments for regular users to top up their wallets
 * Uses test project credentials for sandbox environment
 */
class ClientPaymentsController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['callback']);
    }

    /**
     * Top up wallet using Paysera
     *
     * @return \Cake\Http\Response|null
     */
    public function topup()
    {
        $this->loadModel('Wallets');

        // Get current user's wallet
        $currentUserId = $this->Auth->user('id');
        $wallet = $this->Wallets->find()
            ->where(['user_id' => $currentUserId])
            ->first();

        if (!$wallet) {
            $this->Flash->error('You do not have a wallet. Please contact support.');
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }

        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $amount = floatval($data['amount']);

            if ($amount <= 0) {
                $this->Flash->error('Please enter a valid amount');
                $this->set(compact('wallet'));
                return;
            }

            if ($amount < 1 || $amount > 1000) {
                $this->Flash->error('Amount must be between €1 and €1000');
                $this->set(compact('wallet'));
                return;
            }

            // Build Paysera payment request
            $paymentFormData = $this->buildPayseraRequest($wallet, $amount);
            if ($paymentFormData !== false) {
                $this->set('paymentFormData', $paymentFormData);
                $this->set('amount', $amount);
                $this->set(compact('wallet'));
                return;
            } else {
                $this->Flash->error('Failed to create payment request. Please try again.');
            }
        }

        $this->set(compact('wallet'));
    }

    /**
     * Build Paysera payment request using WebToPay library
     *
     * @param \App\Model\Entity\Wallet $wallet
     * @param float $amount
     * @return array|false Payment form data or false on failure
     */
    private function buildPayseraRequest($wallet, $amount)
    {
        try {
            // Paysera test project credentials
            $projectId = 252027;
            $signPassword = '085d122bfbaa0ed8e0ea244c7ce0208c';

            $requestData = [
                'sign_password' => $signPassword,
                'projectid' => $projectId,
                'orderid' => 'wallet_' . $wallet->id . '_' . time(),
                'amount' => intval($amount * 100), // Convert to cents
                'currency' => 'EUR',
                'accepturl' => 'http://localhost:8765/client-payments/success',
                'cancelurl' => 'http://localhost:8765/client-payments/topup',
                'callbackurl' => 'http://localhost:8765/client-payments/callback',
                'test' => 1,
                'p_firstname' => 'Test',
                'p_lastname' => 'User',
                'p_email' => $this->Auth->user('email'),
                'lang' => 'ENG'
            ];

            // Use WebToPay library to build request data for POST form
            $requestFormData = \WebToPay::buildRequest($requestData);

            // Add payment URL for POST submission
            $requestFormData['_payment_url'] = \WebToPay::getPaymentUrl('ENG');

            return $requestFormData;

        } catch (\Exception $e) {
            $this->log('Paysera payment error: ' . $e->getMessage(), 'error');
            return false;
        }
    }

    /**
     * Handle Paysera callback using WebToPay library
     */
    public function callback()
    {
        try {
            // Handle both GET and POST data as Paysera callbacks can come via either method
            $data = array_merge($this->request->getQuery(), $this->request->getData());

            if (empty($data)) {
                echo 'No data received';
                exit;
            }

            // Paysera test project credentials
            $projectId = 252027;
            $signPassword = '085d122bfbaa0ed8e0ea244c7ce0208c';

            // Use WebToPay library to validate and parse callback data
            $response = \WebToPay::validateAndParseData($data, $projectId, $signPassword);

            // Check if payment was successful
            if ($response['status'] == 1) {
                // Extract wallet ID from order ID
                $orderParts = explode('_', $response['orderid']);
                if (count($orderParts) >= 2 && $orderParts[0] === 'wallet') {
                    $walletId = intval($orderParts[1]);
                    $amount = floatval($response['amount']) / 100; // Convert from cents

                    $this->loadModel('Wallets');
                    $this->loadModel('Transactions');

                    $wallet = $this->Wallets->get($walletId);

                    // Update wallet balance
                    $wallet->balance += $amount;
                    $this->Wallets->save($wallet);

                    // Create transaction record
                    $transaction = $this->Transactions->newEntity([
                        'wallet_id' => $wallet->id,
                        'type' => 'deposit',
                        'amount' => $amount,
                        'description' => 'Wallet top-up via Paysera (Order: ' . $response['orderid'] . ')'
                    ]);
                    $this->Transactions->save($transaction);
                }
            }

            echo 'OK';
            exit;

        } catch (\Exception $e) {
            $this->log('Paysera callback error: ' . $e->getMessage(), 'error');
            echo 'Error: ' . $e->getMessage();
            exit;
        }
    }

    /**
     * Success page after payment
     */
    public function success()
    {
        $this->Flash->success('Payment completed successfully! Your wallet has been topped up.');
        return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
    }
}
