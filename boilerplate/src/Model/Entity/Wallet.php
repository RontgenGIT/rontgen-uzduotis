<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Wallet Entity
 *
 * @property int $id
 * @property int|null $user_id
 * @property float $balance
 * @property string $currency
 * @property \Cake\I18n\FrozenTime|null $created
 * @property \Cake\I18n\FrozenTime|null $modified
 *
 * @property \App\Model\Entity\User $user
 * @property \App\Model\Entity\Transaction[] $transactions
 */
class Wallet extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'user_id' => true,
        'balance' => true,
        'currency' => true,
        'address' => true,
        'created' => true,
        'modified' => true,
        'user' => true,
        'transactions' => true,
    ];

    /**
     * Generate a unique wallet address
     *
     * @return string
     */
    public function generateAddress()
    {
        // Generate a unique address using a combination of timestamp and random string
        $timestamp = time();
        $random = bin2hex(random_bytes(8));
        return 'WLT' . strtoupper($timestamp . $random);
    }
}
