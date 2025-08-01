<?php

namespace App\Service;

use Cake\ORM\TableRegistry;

class InvestmentCriteriaService
{
    private $userId;
    private $criteria;

    public function __construct($userId)
    {
        $this->userId = $userId;
        $this->criteria = TableRegistry::getTableLocator()
            ->get('InvestmentCriteria')
            ->find()
            ->where(['user_id' => $userId])
            ->first();
    }

    private function addFilterIfColumnExists($query, $column, $condition, $columns)
    {
        if (in_array($column, $columns)) {
            $query->where($condition);
        }
    }

    private function isSelected($value)
    {
        if (is_array($value)) {
            return !empty($value);
        }
        return $value !== '' && $value !== null;
    }

    public function filterLoans($loansTable)
    {
        $query = $loansTable->find()->where(['status' => 'approved']);

        if (!$this->criteria) {
            return $query;
        }

        $columns = $loansTable->getSchema()->columns();

        if ($this->criteria->require_collateral === true) {
            $this->addFilterIfColumnExists($query, 'collateral', ['collateral' => true], $columns);
        }
        if ($this->criteria->require_real_estate === true) {
            $this->addFilterIfColumnExists($query, 'real_estate', ['real_estate' => true], $columns);
        }
        if ($this->isSelected($this->criteria->preferred_region)) {
            $regions = array_map('trim', explode(',', $this->criteria->preferred_region));
            $this->addFilterIfColumnExists($query, 'country', ['country IN' => $regions], $columns);
        }
        if ($this->isSelected($this->criteria->risk_tolerance)) {
            $riskMap = ['very low' => 1, 'low' => 2, 'medium' => 3, 'high' => 4, 'very high' => 5];
            $targetRisk = $riskMap[strtolower($this->criteria->risk_tolerance)] ?? null;
            if ($targetRisk !== null) {
                $this->addFilterIfColumnExists($query, 'risk', ['risk <=' => $targetRisk], $columns);
            }
        }
        if ($this->isSelected($this->criteria->min_loan_amount)) {
            $this->addFilterIfColumnExists($query, 'amount', ['amount >=' => (float)$this->criteria->min_loan_amount], $columns);
        }
        if ($this->isSelected($this->criteria->max_loan_amount)) {
            $this->addFilterIfColumnExists($query, 'amount', ['amount <=' => (float)$this->criteria->max_loan_amount], $columns);
        }
        if ($this->isSelected($this->criteria->min_credit_score)) {
            $this->addFilterIfColumnExists($query, 'credit_score', ['credit_score >=' => (int)$this->criteria->min_credit_score], $columns);
        }
        if ($this->isSelected($this->criteria->loan_purpose)) {
            $this->addFilterIfColumnExists($query, 'purpose', ['purpose LIKE' => '%' . $this->criteria->loan_purpose . '%'], $columns);
        }
        if ($this->isSelected($this->criteria->business_or_individual)) {
            $this->addFilterIfColumnExists($query, 'business_or_individual', ['business_or_individual' => $this->criteria->business_or_individual], $columns);
        }

        return $query;
    }
}
