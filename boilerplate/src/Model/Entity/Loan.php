<?php

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Loan extends Entity
{
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
