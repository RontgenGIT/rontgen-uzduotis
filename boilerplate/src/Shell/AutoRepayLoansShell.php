<?php

namespace App\Shell;

use Cake\Console\Shell;
use App\Service\TransactionLogger;

class AutoRepayLoansShell extends Shell
{
    public function main()
    {
        $loansTable = $this->getTableLocator()->get('Loans');
        $walletsTable = $this->getTableLocator()->get('Wallets');
        $investmentsTable = $this->getTableLocator()->get('Investments');
        $today = date('Y-m-d');

        $loans = $loansTable->find()
            ->where([
                'return_date <=' => $today,
                'repaid' => false,
                'status' => 'finished'
            ])
            ->contain(['Investments'])
            ->all();

        foreach ($loans as $loan) {
            $totalInvested = $investmentsTable->find()->where(['loan_id' => $loan->id])->sumOf('amount');
            if ($totalInvested < $loan->amount) {
                $this->out("Loan #{$loan->id}: Not fully funded, skipping.");
                continue;
            }
            if ($loan->repaid) {
                $this->out("Loan #{$loan->id}: Already repaid, skipping.");
                continue;
            }

            $borrowerWallet = $walletsTable->find()->where(['user_id' => $loan->user_id])->first();
            $totalRepay = 0;

            foreach ($loan->investments as $investment) {
                $interest = $investment->amount * ($loan->interest_rate / 100);
                $repayAmount = $investment->amount + $interest;
                $totalRepay += $repayAmount;

                $investorWallet = $walletsTable->find()->where(['user_id' => $investment->user_id])->first();
                if ($investorWallet) {
                    $investorWallet->balance += $repayAmount;
                    $walletsTable->save($investorWallet);

                    TransactionLogger::record(
                        $investorWallet->id,
                        $repayAmount,
                        'receive_repayment',
                        $loan->id,
                        "Received repayment for loan {$loan->id} from borrower {$loan->user_id}"
                    );
                }
                if ($borrowerWallet) {
                    TransactionLogger::record(
                        $borrowerWallet->id,
                        -$repayAmount,
                        'repay',
                        $loan->id,
                        "Repayment to investor {$investment->user_id} for loan {$loan->id}"
                    );
                }
            }
            if ($borrowerWallet) {
                $borrowerWallet->balance -= $totalRepay;
                $walletsTable->save($borrowerWallet);
            }
            $loan->repaid = true;
            $loansTable->save($loan);
            $this->out("Loan {$loan->id}: Investors repaid successfully.");
        }
    }
}
