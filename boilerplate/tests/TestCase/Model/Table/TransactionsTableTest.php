<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TransactionsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TransactionsTable Test Case
 */
class TransactionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\TransactionsTable
     */
    public $Transactions;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Transactions',
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
        $config = TableRegistry::getTableLocator()->exists('Transactions') ? [] : ['className' => TransactionsTable::class];
        $this->Transactions = TableRegistry::getTableLocator()->get('Transactions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Transactions);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->assertInstanceOf('App\Model\Table\TransactionsTable', $this->Transactions);
        $this->assertEquals('transactions', $this->Transactions->getTable());
        $this->assertEquals('id', $this->Transactions->getPrimaryKey());
        $this->assertEquals('id', $this->Transactions->getDisplayField());

        $this->assertTrue($this->Transactions->hasBehavior('Timestamp'));
        $this->assertTrue($this->Transactions->hasAssociation('Wallets'));
        $this->assertEquals('belongsTo', $this->Transactions->getAssociation('Wallets')->type());
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $validator = $this->Transactions->getValidator('default');

        $this->assertTrue($validator->hasField('wallet_id'));
        $this->assertTrue($validator->hasField('type'));
        $this->assertTrue($validator->hasField('amount'));
        $this->assertTrue($validator->hasField('description'));

        // Test valid data
        $transaction = $this->Transactions->newEntity([
            'wallet_id' => 1,
            'type' => 'deposit',
            'amount' => 50.00,
            'description' => 'Test transaction'
        ]);
        $this->assertEmpty($transaction->getErrors());

        // Test invalid amount (zero)
        $transaction = $this->Transactions->newEntity([
            'wallet_id' => 1,
            'type' => 'deposit',
            'amount' => 0,
            'description' => 'Test transaction'
        ]);
        $this->assertNotEmpty($transaction->getErrors('amount'));

        // Test empty type
        $transaction = $this->Transactions->newEntity([
            'wallet_id' => 1,
            'type' => '',
            'amount' => 50.00,
            'description' => 'Test transaction'
        ]);
        $this->assertNotEmpty($transaction->getErrors('type'));
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        // Test that wallet_id must exist in wallets table
        $transaction = $this->Transactions->newEntity([
            'wallet_id' => 999, // Non-existent wallet
            'type' => 'deposit',
            'amount' => 50.00,
            'description' => 'Test transaction'
        ]);
        $this->assertFalse($this->Transactions->save($transaction));
        $this->assertNotEmpty($transaction->getErrors('wallet_id'));

        // Test valid foreign key
        $transaction = $this->Transactions->newEntity([
            'wallet_id' => 1, // Existing wallet from fixture
            'type' => 'deposit',
            'amount' => 50.00,
            'description' => 'Test transaction'
        ]);
        $this->assertTrue($this->Transactions->save($transaction));
    }
}
