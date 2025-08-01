<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Service\TransactionLogger;
use App\Service\LoanEligibilityService;
use App\Service\InvestmentCriteriaService;

class LoansController extends AppController
{
    public function view($id)
    {
        $loan = $this->Loans->get($id, [
            'contain' => ['Users', 'Investments' => ['Users']]
        ]);
        $investments = $loan->investments;
        $this->set(compact('loan', 'investments'));
    }

    public function adminList()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $loans = $this->Loans->find()
            ->order(['created' => 'DESC'])
            ->toList();

        $this->set(compact('loans'));
    }

    public function adminApprove($id = null)
    {
        $loan = $this->Loans->get($id);
        if ($loan->status !== 'requested') {
            $this->Flash->error('Loan is not in requested state.');
            return $this->redirect(['action' => 'adminList']);
        }
        $loan->status = 'approved';

        if ($this->Loans->save($loan)) {
            $this->Flash->success('Loan approved.');
        } else {
            $this->Flash->error('Approval failed.');
        }
        return $this->redirect(['action' => 'adminList']);
    }

    public function adminReject($id = null)
    {
        $loan = $this->Loans->get($id);
        if ($loan->status !== 'requested') {
            $this->Flash->error('Loan is not in requested state.');
            return $this->redirect(['action' => 'adminList']);
        }
        $loan->status = 'rejected';

        if ($this->Loans->save($loan)) {
            $this->Flash->success('Loan rejected.');
        } else {
            $this->Flash->error('Rejection failed.');
        }
        return $this->redirect(['action' => 'adminList']);
    }

    public function adminEdit($id = null)
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $loan = $this->Loans->get($id);

        if ($this->request->is(['post', 'put', 'patch'])) {
            $loan = $this->Loans->patchEntity($loan, $this->request->getData());
            if ($this->Loans->save($loan)) {
                $this->Flash->success('Loan updated successfully.');
                return $this->redirect(['action' => 'adminList']);
            }
            $this->Flash->error('Failed to update loan.');
        }

        $this->set(compact('loan'));
    }

    public function request()
    {
        $loanRequest = $this->Loans->newEntity();
        $userId = $this->Auth->user('id');
        $eligibilityService = new LoanEligibilityService();

        if ($this->request->is('post')) {
            $data = $this->request->getData();

            if (!$eligibilityService->isEligible($data)) {
                $this->Flash->error('You are not eligible for this loan.');
                $this->set(compact('loanRequest'));
                return;
            }

            $returnDate = $data['return_date'] ?? null;
            $returnDateStr = is_array($returnDate)
                ? sprintf(
                    '%04d-%02d-%02d',
                    $returnDate['year'] ?? 0,
                    $returnDate['month'] ?? 0,
                    $returnDate['day'] ?? 0
                )
                : $returnDate;

            if (!$returnDateStr || strtotime($returnDateStr) < strtotime('+1 day')) {
                $this->Flash->error('Return date must be at least tomorrow.');
                $this->set(compact('loanRequest'));
                return;
            }

            $amount = isset($data['amount']) ? (float)$data['amount'] : 0;
            $collateral = isset($data['collateral']) ? (int)$data['collateral'] : 0;
            $realEstate = isset($data['real_estate']) ? (int)$data['real_estate'] : 0;
            $income = isset($data['income']) ? (float)$data['income'] : 0;

            $riskLevel = $this->Loans->calculateRiskLevel($amount, $collateral, $realEstate, $income);
            $data['risk'] = $riskLevel;

            $loanRequest = $this->Loans->patchEntity($loanRequest, $data);
            $loanRequest->user_id = $userId;
            $loanRequest->status = 'requested';

            if ($this->Loans->save($loanRequest)) {
                $this->Flash->success('Loan request submitted! Your risk level: ' . $riskLevel);
                return $this->redirect(['action' => 'myLoans']);
            }
            $this->Flash->error('Failed to submit request.');
        }
        $this->set(compact('loanRequest'));
    }

    public function myLoans()
    {
        $userId = $this->Auth->user('id');
        $loans = $this->Loans->find()->where(['user_id' => $userId])->toList();
        $this->set(compact('loans'));
    }

    public function investList()
    {
        $userId = $this->Auth->user('id');
        $criteriaService = new InvestmentCriteriaService($userId);

        $loans = $criteriaService->filterLoans($this->Loans);

        $this->loadModel('Investments');
        foreach ($loans as $loan) {
            $loan->total_invested = $this->Investments->find()
                ->where(['loan_id' => $loan->id])
                ->sumOf('amount');
        }

        $this->set(compact('loans'));
    }

    public function invest($loanId = null)
    {
        $this->loadModel('Investments');
        $this->loadModel('Wallets');
        $loan = $this->Loans->get($loanId);

        $currentUserId = $this->Auth->user('id');
        if ($loan->user_id == $currentUserId) {
            $this->Flash->error('You cannot invest in your own loan.');
            return $this->redirect(['action' => 'investList']);
        }

        if ($loan->status !== 'approved') {
            $this->Flash->error('Cannot invest in this loan.');
            return $this->redirect(['action' => 'investList']);
        }

        $totalInvested = $this->Investments->find()
            ->where(['loan_id' => $loanId])
            ->sumOf('amount');
        $amountLeft = $loan->amount - $totalInvested;

        $investment = $this->Investments->newEntity();
        if ($this->request->is('post')) {
            $investmentAmount = (float)$this->request->getData('amount');

            $investorWallet = $this->Wallets->find()->where(['user_id' => $currentUserId])->first();
            if (!$investorWallet || $investorWallet->balance < $investmentAmount) {
                $this->Flash->error('Insufficient funds in your wallet. Your balance: ' . ($investorWallet ? $investorWallet->balance : 0));
                $this->set(compact('loan', 'investment', 'amountLeft'));
                return;
            }

            if ($investmentAmount > $amountLeft) {
                $this->Flash->error('Cannot invest more than the amount left: ' . $amountLeft);
                $this->set(compact('loan', 'investment', 'amountLeft'));
                return;
            }

            $investment = $this->Investments->patchEntity($investment, $this->request->getData());
            $investment->loan_id = $loanId;
            $investment->user_id = $currentUserId;
            $investment->status = 'funded';

            if ($this->Investments->save($investment)) {
                $this->transferFunds($investment, $loan);

                $totalInvested = $this->Investments->find()
                    ->where(['loan_id' => $loanId])
                    ->sumOf('amount');
                if ($totalInvested >= $loan->amount) {
                    $loan->status = 'finished';
                    $this->Loans->save($loan);
                }

                $this->Flash->success('Investment successful!');
                return $this->redirect(['controller' => 'Investments', 'action' => 'myInvestments']);
            }
            $this->Flash->error('Investment failed.');
        }
        $this->set(compact('loan', 'investment', 'amountLeft'));
    }

    public function repayInvestors($loanId)
    {
        $this->loadModel('Wallets');

        $loan = $this->Loans->get($loanId, ['contain' => ['Investments']]);
        $currentUserId = $this->Auth->user('id');

        if ($loan->user_id !== $currentUserId) {
            $this->Flash->error('You are not authorized.');
            return $this->redirect(['action' => 'myLoans']);
        }

        $totalInvested = $this->Loans->getTotalInvested($loan->id);
        if ($totalInvested < $loan->amount) {
            $this->Flash->error('Cannot repay: loan is not fully funded yet.');
            return $this->redirect(['action' => 'myLoans']);
        }

        if ($loan->repaid) {
            $this->Flash->info('Already repaid.');
            return $this->redirect(['action' => 'myLoans']);
        }

        $loan->repaid = true;
        if (!$this->Loans->save($loan)) {
            $this->Flash->error('Could not update repayment status.');
            return $this->redirect(['action' => 'myLoans']);
        }

        $recipientWallet = $this->Wallets->find()->where(['user_id' => $loan->user_id])->first();
        $totalRepay = 0;

        foreach ($loan->investments as $investment) {
            $interest = $investment->amount * ($loan->interest_rate / 100);
            $repayAmount = $investment->amount + $interest;
            $totalRepay += $repayAmount;

            $investorWallet = $this->Wallets->find()->where(['user_id' => $investment->user_id])->first();
            if ($investorWallet) {
                $investorWallet->balance += $repayAmount;
                $this->Wallets->save($investorWallet);
                TransactionLogger::record($investorWallet->id, $repayAmount, 'repay', $loan->id, "Repayment for loan #{$loan->id}");
            }
        }

        if ($recipientWallet) {
            $recipientWallet->balance -= $totalRepay;
            $this->Wallets->save($recipientWallet);
        }

        $this->Flash->success('Investors repaid successfully.');
        return $this->redirect(['action' => 'myLoans']);
    }

    private function transferFunds($investment, $loan)
    {
        $this->loadModel('Wallets');

        $senderWallet = $this->Wallets->find()->where(['user_id' => $investment->user_id])->first();
        $recipientWallet = $this->Wallets->find()->where(['user_id' => $loan->user_id])->first();

        $amount = $investment->amount;

        if ($senderWallet && $recipientWallet && $senderWallet->balance >= $amount) {
            $senderWallet->balance -= $amount;
            $recipientWallet->balance += $amount;
            $this->Wallets->save($senderWallet);
            $this->Wallets->save($recipientWallet);
            TransactionLogger::record($senderWallet->id, -$amount, 'invest', $investment->id, "Invested in loan #{$loan->id}");
            TransactionLogger::record($recipientWallet->id, $amount, 'receive', $loan->id, "Received investment for loan #{$loan->id}");
        }
    }
}
