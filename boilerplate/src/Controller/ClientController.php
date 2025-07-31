<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Auth\DefaultPasswordHasher;

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
     * Profile method - user can view and edit their basic profile info
     *
     * @return \Cake\Http\Response|null
     */
    public function profile()
    {
        $userId = $this->request->getSession()->read('Auth.User.id');

            if (!$userId) {
                $this->Flash->error('You must be logged in to access your profile');
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }

            $this->loadModel('Users');
            $user = $this->Users->get($userId);

            if ($this->request->is(['post', 'put'])) {
            $data = $this->request->getData();

            $hasher = new DefaultPasswordHasher();
            if (!$hasher->check($data['current_password'], $user->password)) {
                $this->Flash->error(__('The current password is incorrect'));
                $user = $this->Users->patchEntity($user, $data, ['validate' => false]);
                $user->setErrors(['current_password' => ['_empty' => '']]); // to mark as error maybe
            } else {
                $user = $this->Users->patchEntity($user, $data);
                if ($this->Users->save($user)) {
                    // after updating profile authuser email change
                    $authUser = $this->request->getSession()->read('Auth.User');
                    $authUser['email'] = $user->email;
                    $this->request->getSession()->write('Auth.User', $authUser);

                    $this->Flash->success(__('Your profile has been updated'));
                    return $this->redirect(['action' => 'profile']);
                } else {
                    $this->Flash->error(__('Unable to update your profile'));
                }
            }
        }

        $this->set(compact('user'));
    }
}
