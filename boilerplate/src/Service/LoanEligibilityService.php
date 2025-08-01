<?php

namespace App\Service;

class LoanEligibilityService
{
    public function isEligible(array $data): bool
    {
        $income = isset($data['income']) ? floatval($data['income']) : 0;
        $creditScore = isset($data['credit_score']) ? intval($data['credit_score']) : 0;

        if ($income > 500 && $creditScore > 400) {
            return true;
        }
        return false;
    }
}
