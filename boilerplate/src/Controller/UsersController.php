<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['register', 'logout']);
    }

    public function login()
    {
        // Get seed users for display in template
        $seedUsers = [
            [
                'email' => 'admin@test.com',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
                'email' => 'user1@test.com',
                'password' => 'user123',
                'role' => 'user'
            ],
            [
                'email' => 'user2@test.com',
                'password' => 'user123',
                'role' => 'user'
            ]
        ];
        $this->set('seedUsers', $seedUsers);

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);

                // Role-based redirect
                if (isset($user['role']) && $user['role'] === 'admin') {
                    // Admin users go to admin panel
                    return $this->redirect(['action' => 'index']);
                } else {
                    // Regular users go to client dashboard
                    return $this->redirect(['controller' => 'Client', 'action' => 'dashboard']);
                }
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

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

        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $user = $this->Users->get($id, [
            'contain' => ['Wallets'],
        ]);

        $this->set('user', $user);
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

        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->loadModel('Wallets');
                $wallet = $this->Wallets->newEntity([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
                $this->Wallets->save($wallet);
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Register method for regular users
     *
     * @return \Cake\Http\Response|null Redirects on successful registration, renders view otherwise.
     */
    public function register()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            $data['role'] = 'user'; // Set default role for regular users

            $user = $this->Users->patchEntity($user, $data);
            if ($this->Users->save($user)) {
                // Create wallet for the new user
                $this->loadModel('Wallets');
                $wallet = $this->Wallets->newEntity([
                    'user_id' => $user->id,
                    'balance' => 0,
                ]);
                $this->Wallets->save($wallet);

                $this->Flash->success(__('Registration successful! Welcome to the platform.'));
                $this->Auth->setUser($user);
                return $this->redirect(['controller' => 'Wallets', 'action' => 'index']);
            }
            $this->Flash->error(__('Registration failed. Please, try again.'));
        }
        $this->set(compact('user'));
    }
}
