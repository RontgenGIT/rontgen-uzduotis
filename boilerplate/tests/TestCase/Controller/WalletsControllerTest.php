<?php
namespace App\Test\TestCase\Controller;

use App\Controller\WalletsController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\WalletsController Test Case
 *
 * @uses \App\Controller\WalletsController
 */
class WalletsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Wallets',
        'app.Users',
        'app.Transactions',
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

        $this->get('/wallets');
        $this->assertResponseOk();
        $this->assertResponseContains('Wallets');
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

        $this->get('/wallets');
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

        $this->get('/wallets/view/1');
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

        $this->get('/wallets/view/1');
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

        $this->get('/wallets/add');
        $this->assertResponseOk();
        $this->assertResponseContains('Add Wallet');
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

        $this->post('/wallets/add', [
            'user_id' => 1,
            'balance' => 100.00,
            'currency' => 'EUR'
        ]);

        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The wallet has been saved.');
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

        $this->get('/wallets/add');
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

        $this->get('/wallets/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Edit Wallet');
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

        $this->post('/wallets/edit/1', [
            'user_id' => 1,
            'balance' => 150.00,
            'currency' => 'EUR'
        ]);

        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The wallet has been saved.');
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

        $this->get('/wallets/edit/1');
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

        $this->post('/wallets/delete/1');
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The wallet has been deleted.');
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

        $this->post('/wallets/delete/1');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }
}
