<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Client Controller
 *
 * Main dashboard for regular users (non-admin)
 * Provides wallet overview and access to client features
 */
class ClientController extends AppController
{
    /**
     * Dashboard method - main page for regular users
     *
     * @return \Cake\Http\Response|null
     */
    public function dashboard()
    {
        // Load required models
        $this->loadModel('Wallets');
        $this->loadModel('Transactions');

        // Get current user's wallet
        $currentUserId = $this->Auth->user('id');
        $wallet = $this->Wallets->find()
            ->where(['user_id' => $currentUserId])
            ->first();

        if (!$wallet) {
            $this->Flash->error('You do not have a wallet. Please contact support.');
            return $this->redirect(['controller' => 'Users', 'action' => 'logout']);
        }

        // Get recent transactions for this wallet (last 10)
        $recentTransactions = $this->Transactions->find()
            ->where(['wallet_id' => $wallet->id])
            ->order(['created' => 'DESC'])
            ->limit(10)
            ->toArray();

        // Calculate some basic stats
        $totalIncoming = $this->Transactions->find()
            ->where([
                'wallet_id' => $wallet->id,
                'amount >' => 0
            ])
            ->sumOf('amount') ?: 0;

        $totalOutgoing = abs($this->Transactions->find()
            ->where([
                'wallet_id' => $wallet->id,
                'amount <' => 0
            ])
            ->sumOf('amount') ?: 0);

        $this->set(compact('wallet', 'recentTransactions', 'totalIncoming', 'totalOutgoing'));
    }

    /**
     * Profile method - user can view their basic profile info
     *
     * @return \Cake\Http\Response|null
     */
    public function profile()
    {
        $this->loadModel('Users');
        $currentUserId = $this->Auth->user('id');

        $user = $this->Users->get($currentUserId, [
            'contain' => ['Wallets']
        ]);

        $this->set(compact('user'));
    }
}
