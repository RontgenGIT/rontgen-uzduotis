<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TransactionsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\TransactionsController Test Case
 *
 * @uses \App\Controller\TransactionsController
 */
class TransactionsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Transactions',
        'app.Wallets',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
    }

    /**
     * Test index method with admin access
     *
     * @return void
     */
    public function testIndex()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->get('/transactions');
        $this->assertResponseOk();
        $this->assertResponseContains('Transactions');
    }

    /**
     * Test index method without admin access
     *
     * @return void
     */
    public function testIndexAccessDenied()
    {
        // Login as regular user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'user'
                ]
            ]
        ]);

        $this->get('/transactions');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }

    /**
     * Test view method with admin access
     *
     * @return void
     */
    public function testView()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->get('/transactions/view/1');
        $this->assertResponseOk();
    }

    /**
     * Test view method without admin access
     *
     * @return void
     */
    public function testViewAccessDenied()
    {
        // Login as regular user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'user'
                ]
            ]
        ]);

        $this->get('/transactions/view/1');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }

    /**
     * Test add method with admin access - GET request
     *
     * @return void
     */
    public function testAdd()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->get('/transactions/add');
        $this->assertResponseOk();
        $this->assertResponseContains('Add Transaction');
    }

    /**
     * Test add method with admin access - POST request success
     *
     * @return void
     */
    public function testAddPostSuccess()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->post('/transactions/add', [
            'wallet_id' => 1,
            'type' => 'deposit',
            'amount' => 50.00,
            'description' => 'Test transaction'
        ]);

        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The transaction has been saved.');
    }

    /**
     * Test add method without admin access
     *
     * @return void
     */
    public function testAddAccessDenied()
    {
        // Login as regular user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'user'
                ]
            ]
        ]);

        $this->get('/transactions/add');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }

    /**
     * Test edit method with admin access - GET request
     *
     * @return void
     */
    public function testEdit()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->get('/transactions/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Edit Transaction');
    }

    /**
     * Test edit method with admin access - POST request success
     *
     * @return void
     */
    public function testEditPostSuccess()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->post('/transactions/edit/1', [
            'wallet_id' => 1,
            'type' => 'withdrawal',
            'amount' => 25.00,
            'description' => 'Updated test transaction'
        ]);

        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The transaction has been saved.');
    }

    /**
     * Test edit method without admin access
     *
     * @return void
     */
    public function testEditAccessDenied()
    {
        // Login as regular user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'user'
                ]
            ]
        ]);

        $this->get('/transactions/edit/1');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }

    /**
     * Test delete method with admin access - success
     *
     * @return void
     */
    public function testDelete()
    {
        // Login as admin user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->post('/transactions/delete/1');
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The transaction has been deleted.');
    }

    /**
     * Test delete method without admin access
     *
     * @return void
     */
    public function testDeleteAccessDenied()
    {
        // Login as regular user
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'user'
                ]
            ]
        ]);

        $this->post('/transactions/delete/1');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }
}
