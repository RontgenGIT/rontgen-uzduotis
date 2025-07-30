<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * WalletsFixture
 */
class WalletsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => false, 'default' => null, 'autoIncrement' => true, 'precision' => null, 'comment' => null],
        'user_id' => ['type' => 'integer', 'length' => null, 'unsigned' => false, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'autoIncrement' => null],
        'balance' => ['type' => 'float', 'length' => 10, 'precision' => 2, 'unsigned' => false, 'null' => false, 'default' => '0', 'comment' => null],
        'currency' => ['type' => 'string', 'length' => 3, 'null' => false, 'default' => 'EUR', 'precision' => null, 'comment' => null, 'fixed' => null, 'collate' => null],
        'address' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null, 'fixed' => null, 'collate' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'precision' => null, 'comment' => null],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'user_id_fk' => ['type' => 'foreign', 'columns' => ['user_id'], 'references' => ['users', 'id'], 'update' => 'noAction', 'delete' => 'cascade', 'length' => []],
        ],
    ];
    // @codingStandardsIgnoreEnd
    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'balance' => 1,
                'currency' => 'EUR',
                'address' => 'WLT1234567890ABCDEF01',
                'created' => '2025-07-29 13:19:00',
                'modified' => '2025-07-29 13:19:00',
            ],
            [
                'id' => 2,
                'user_id' => 2,
                'balance' => 0,
                'currency' => 'EUR',
                'address' => 'WLT1234567890ABCDEF02',
                'created' => '2025-07-29 13:19:00',
                'modified' => '2025-07-29 13:19:00',
            ],
        ];
        parent::init();
    }
}
