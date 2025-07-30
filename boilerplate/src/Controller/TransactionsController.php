<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Transactions Controller
 *
 * @property \App\Model\Table\TransactionsTable $Transactions
 *
 * @method \App\Model\Entity\Transaction[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TransactionsController extends AppController
{
    /**
     * Check if current user is admin
     *
     * @return bool
     */
    private function isAdmin()
    {
        $user = $this->Auth->user();
        return $user && isset($user['role']) && $user['role'] === 'admin';
    }

    /**
     * Require admin access for action
     */
    private function requireAdmin()
    {
        if (!$this->isAdmin()) {
            $this->Flash->error(__('Access denied. Admin privileges required.'));
            return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
        }
        return true;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->paginate = [
            'contain' => ['Wallets'],
        ];
        $transactions = $this->paginate($this->Transactions);

        $this->set(compact('transactions'));
    }

    /**
     * View method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $transaction = $this->Transactions->get($id, [
            'contain' => ['Wallets'],
        ]);

        $this->set('transaction', $transaction);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $transaction = $this->Transactions->newEntity();
        if ($this->request->is('post')) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $wallets = $this->Transactions->Wallets->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'wallets'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $transaction = $this->Transactions->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $transaction = $this->Transactions->patchEntity($transaction, $this->request->getData());
            if ($this->Transactions->save($transaction)) {
                $this->Flash->success(__('The transaction has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The transaction could not be saved. Please, try again.'));
        }
        $wallets = $this->Transactions->Wallets->find('list', ['limit' => 200]);
        $this->set(compact('transaction', 'wallets'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Transaction id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->request->allowMethod(['post', 'delete']);
        $transaction = $this->Transactions->get($id);
        if ($this->Transactions->delete($transaction)) {
            $this->Flash->success(__('The transaction has been deleted.'));
        } else {
            $this->Flash->error(__('The transaction could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
