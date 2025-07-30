<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\WalletsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\WalletsTable Test Case
 */
class WalletsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\WalletsTable
     */
    public $Wallets;

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

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::getTableLocator()->exists('Wallets') ? [] : ['className' => WalletsTable::class];
        $this->Wallets = TableRegistry::getTableLocator()->get('Wallets', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Wallets);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertInstanceOf('App\Model\Table\WalletsTable', $this->Wallets);
        $this->assertEquals('wallets', $this->Wallets->getTable());
        $this->assertEquals('id', $this->Wallets->getPrimaryKey());
        $this->assertEquals('id', $this->Wallets->getDisplayField());

        $this->assertTrue($this->Wallets->hasBehavior('Timestamp'));
        $this->assertTrue($this->Wallets->hasAssociation('Users'));
        $this->assertTrue($this->Wallets->hasAssociation('Transactions'));
        $this->assertEquals('belongsTo', $this->Wallets->getAssociation('Users')->type());
        $this->assertEquals('hasMany', $this->Wallets->getAssociation('Transactions')->type());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $validator = $this->Wallets->getValidator('default');

        $this->assertTrue($validator->hasField('user_id'));
        $this->assertTrue($validator->hasField('balance'));
        $this->assertTrue($validator->hasField('currency'));

        // Test valid data
        $wallet = $this->Wallets->newEntity([
            'user_id' => 1,
            'balance' => 100.50,
            'currency' => 'EUR'
        ]);
        $this->assertEmpty($wallet->getErrors());

        // Test invalid balance (negative)
        $wallet = $this->Wallets->newEntity([
            'user_id' => 1,
            'balance' => -10.00,
            'currency' => 'EUR'
        ]);
        $this->assertNotEmpty($wallet->getErrors('balance'));

        // Test empty currency
        $wallet = $this->Wallets->newEntity([
            'user_id' => 1,
            'balance' => 100.00,
            'currency' => ''
        ]);
        $this->assertNotEmpty($wallet->getErrors('currency'));
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        // Test that user_id must exist in users table
        $wallet = $this->Wallets->newEntity([
            'user_id' => 999, // Non-existent user
            'balance' => 100.00,
            'currency' => 'EUR'
        ]);
        $this->assertFalse($this->Wallets->save($wallet));
        $this->assertNotEmpty($wallet->getErrors('user_id'));

        // Test valid foreign key
        $wallet = $this->Wallets->newEntity([
            'user_id' => 1, // Existing user from fixture
            'balance' => 100.00,
            'currency' => 'EUR'
        ]);
        $this->assertTrue($this->Wallets->save($wallet));
    }
}
