<?php
namespace App\Test\TestCase\Controller;

use App\Controller\TransfersController;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\TransfersController Test Case
 *
 * @uses \App\Controller\TransfersController
 */
class TransfersControllerTest extends TestCase
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
        'app.Transactions',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();
        $this->enableSecurityToken();
    }

    /**
     * Test successful transfer between wallets
     */
    public function testTransferSuccess()
    {
        // Login as user 1 (has balance of 1 EUR)
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        // Transfer 0.50 EUR from wallet 1 to wallet 2
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 2,
            'amount' => 0.50,
            'description' => 'Test transfer'
        ]);

        $this->assertRedirect(['controller' => 'Wallets', 'action' => 'index']);
        $this->assertFlashMessage('Transfer completed successfully');

        // Verify sender wallet balance decreased
        $walletsTable = $this->getTableLocator()->get('Wallets');
        $senderWallet = $walletsTable->get(1);
        $this->assertEquals(0.50, $senderWallet->balance);

        // Verify recipient wallet balance increased
        $recipientWallet = $walletsTable->get(2);
        $this->assertEquals(0.50, $recipientWallet->balance);

        // Verify transactions were created
        $transactionsTable = $this->getTableLocator()->get('Transactions');
        $debitTransaction = $transactionsTable->find()
            ->where(['wallet_id' => 1, 'type' => 'transfer_out'])
            ->first();
        $this->assertNotNull($debitTransaction);
        $this->assertEquals(-0.50, $debitTransaction->amount);

        $creditTransaction = $transactionsTable->find()
            ->where(['wallet_id' => 2, 'type' => 'transfer_in'])
            ->first();
        $this->assertNotNull($creditTransaction);
        $this->assertEquals(0.50, $creditTransaction->amount);
    }

    /**
     * Test transfer with insufficient balance
     */
    public function testTransferInsufficientBalance()
    {
        // Login as user 1 (has balance of 1 EUR)
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        // Try to transfer 2 EUR (more than available balance)
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 2,
            'amount' => 2.00,
            'description' => 'Test transfer with insufficient funds'
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('Insufficient balance for this transfer');

        // Verify balances remain unchanged
        $walletsTable = $this->getTableLocator()->get('Wallets');
        $senderWallet = $walletsTable->get(1);
        $this->assertEquals(1.00, $senderWallet->balance);

        $recipientWallet = $walletsTable->get(2);
        $this->assertEquals(0.00, $recipientWallet->balance);
    }

    /**
     * Test transfer to non-existent wallet
     */
    public function testTransferToNonExistentWallet()
    {
        // Login as user 1
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        // Try to transfer to wallet ID 999 (doesn't exist)
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 999,
            'amount' => 0.50,
            'description' => 'Test transfer to non-existent wallet'
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('Recipient wallet not found');
    }

    /**
     * Test transfer to own wallet (should be prevented)
     */
    public function testTransferToOwnWallet()
    {
        // Login as user 1
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        // Try to transfer to own wallet (wallet 1)
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 1,
            'amount' => 0.50,
            'description' => 'Test transfer to own wallet'
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('Cannot transfer to your own wallet');
    }

    /**
     * Test transfer with invalid amount (negative)
     */
    public function testTransferWithNegativeAmount()
    {
        // Login as user 1
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        // Try to transfer negative amount
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 2,
            'amount' => -0.50,
            'description' => 'Test negative transfer'
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('Transfer amount must be positive');
    }

    /**
     * Test transfer with zero amount
     */
    public function testTransferWithZeroAmount()
    {
        // Login as user 1
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        // Try to transfer zero amount
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 2,
            'amount' => 0,
            'description' => 'Test zero transfer'
        ]);

        $this->assertResponseOk();
        $this->assertResponseContains('Transfer amount must be positive');
    }

    /**
     * Test unauthorized access (not logged in)
     */
    public function testTransferUnauthorized()
    {
        // Don't login - test unauthorized access
        $this->post('/transfers/create', [
            'recipient_wallet_id' => 2,
            'amount' => 0.50,
            'description' => 'Unauthorized transfer'
        ]);

        $this->assertRedirect(['controller' => 'Users', 'action' => 'login']);
    }

    /**
     * Test transfer form display
     */
    public function testTransferFormDisplay()
    {
        // Login as user 1
        $this->session([
            'Auth' => [
                'User' => [
                    'id' => 1,
                    'email' => 'test@test.com'
                ]
            ]
        ]);

        $this->get('/transfers/create');
        $this->assertResponseOk();
        $this->assertResponseContains('Transfer Money');
        $this->assertResponseContains('Recipient Wallet ID');
        $this->assertResponseContains('Amount');
        $this->assertResponseContains('Description');
    }
}
