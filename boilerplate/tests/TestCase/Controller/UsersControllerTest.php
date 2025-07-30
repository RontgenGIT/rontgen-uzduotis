<?php
namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\UsersController
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Wallets',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
    }

    public function testLoginSuccess()
    {
        $this->post('/users/login', [
            'email' => 'test@test.com',
            'password' => 'test'
        ]);
        $this->assertSession(1, 'Auth.User.id');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
    }

    public function testLoginFailure()
    {
        $this->post('/users/login', [
            'email' => 'test@test.com',
            'password' => 'wrongpassword'
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('Invalid username or password, try again');
        $this->assertSession(null, 'Auth.User.id');
    }

    public function testAddSuccessAsAdmin()
    {
        // Login as admin user first
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->post('/users/add', [
            'email' => 'newuser@test.com',
            'password' => 'password',
            'role' => 'user'
        ]);
        $this->assertRedirect(['controller' => 'Users', 'action' => 'index']);
    }

    public function testAddFailureAsAdmin()
    {
        // Login as admin user first
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com',
                    'role' => 'admin'
                ]
            ]
        ]);

        $this->post('/users/add', [
            'email' => 'test@test.com', // Duplicate email should fail
            'password' => 'password',
            'role' => 'user'
        ]);
        $this->assertResponseOk();
        $this->assertResponseContains('The user could not be saved. Please, try again.');
    }

    public function testAddAccessDeniedForRegularUser()
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

        $this->post('/users/add', [
            'email' => 'newuser@test.com',
            'password' => 'password'
        ]);
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
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

        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('Users');
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

        $this->get('/users');
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

        $this->get('/users/view/1');
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

        $this->get('/users/view/1');
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

        $this->get('/users/edit/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Edit User');
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

        $this->post('/users/edit/2', [
            'email' => 'updated@test.com',
            'password' => 'newpassword'
        ]);

        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The user has been saved.');
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

        $this->get('/users/edit/1');
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

        $this->post('/users/delete/2');
        $this->assertRedirect(['action' => 'index']);
        $this->assertFlashMessage('The user has been deleted.');
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

        $this->post('/users/delete/2');
        $this->assertRedirect(['controller' => 'Client', 'action' => 'dashboard']);
        $this->assertFlashMessage('Access denied. Admin privileges required.');
    }
}
