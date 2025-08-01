<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class InvestmentCriteria extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false,
    ];
}