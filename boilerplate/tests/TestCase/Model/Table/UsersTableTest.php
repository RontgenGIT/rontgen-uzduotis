<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    public $Users;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
        'app.Wallets',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = TableRegistry::getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertInstanceOf('App\Model\Table\UsersTable', $this->Users);
        $this->assertEquals('users', $this->Users->getTable());
        $this->assertEquals('id', $this->Users->getPrimaryKey());
        $this->assertEquals('email', $this->Users->getDisplayField());

        $this->assertTrue($this->Users->hasBehavior('Timestamp'));
        $this->assertTrue($this->Users->hasAssociation('Wallets'));
        $this->assertEquals('hasMany', $this->Users->getAssociation('Wallets')->type());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $validator = $this->Users->getValidator('default');

        $this->assertTrue($validator->hasField('email'));
        $this->assertTrue($validator->hasField('password'));

        // Test valid data
        $user = $this->Users->newEntity([
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);
        $this->assertEmpty($user->getErrors());

        // Test invalid email
        $user = $this->Users->newEntity([
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);
        $this->assertNotEmpty($user->getErrors('email'));

        // Test empty email
        $user = $this->Users->newEntity([
            'email' => '',
            'password' => 'password123'
        ]);
        $this->assertNotEmpty($user->getErrors('email'));
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        // Test unique email rule
        $user1 = $this->Users->newEntity([
            'email' => 'unique@test.com',
            'password' => 'password123'
        ]);
        $this->assertTrue($this->Users->save($user1));

        // Try to create another user with same email
        $user2 = $this->Users->newEntity([
            'email' => 'unique@test.com',
            'password' => 'password456'
        ]);
        $this->assertFalse($this->Users->save($user2));
        $this->assertNotEmpty($user2->getErrors('email'));
    }
}
