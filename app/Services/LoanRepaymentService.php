<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanStatuses;

class LoanRepaymentService
{
    public function createRepayment(Loan $loan, float $amount): LoanRepayment
    {
        $deposits = $this->getRequiredDeposits($loan);

        if ($amount < $deposits['min'] || $amount > $deposits['max']) {
            throw new \InvalidArgumentException('Invalid repayment amount');
        }

        $repayment = new LoanRepayment([
            'amount' => $amount,
            'loan_id' => $loan->id,
        ]);

        $repayment->save();

        // Check if we can mark the loan as PAID
        if ($amount == $deposits['max']) {
            $loan->status_id = LoanStatuses::PAID;
            $loan->save();
        }

        return $repayment;
    }

    private function getRequiredDeposits(Loan $loan): array
    {
        $totalAmount = $loan->amount;
        $repayments = $loan->repayments;
        $max = $totalAmount - $repayments->sum('amount');
        $min = ($max) / ($loan->term_duration - $repayments->count());
        return ['max' => $max, 'min' => $min];
    }
}
