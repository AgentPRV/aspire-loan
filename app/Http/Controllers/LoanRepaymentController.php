<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanRepayment;
use App\Models\LoanStatuses;
use Illuminate\Http\Request;

class LoanRepaymentController extends Controller
{
    public function create(Request $request, $loanId)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $loan = Loan::with('repayments')->where([
            'id' => $loanId,
            'user_id' => $request->user()->id
        ])->first();

        if(!$loan){
            return response(["message"=> "Invalid loan ID"], 412);
        }

        $deposits = $this->getRequiredDeposits($loan);
        
        if($request->input('amount') < $deposits['min'] || $request->input('amount') > $deposits['max']){
            return response(["message"=> "Amount is invalid"], 412);
        }

        $repayment = new LoanRepayment([
            'amount' => $request->input('amount'),
            'loan_id' => $loan->id,
        ]);

        $repayment->save();

        // check if we can mark the loan as PAID
        if($request->input('amount') == $deposits['max']){
            $loan->status_id = LoanStatuses::PAID;
            $loan->save();
        }

        return response()->json($repayment, 201);
    }

    private function getRequiredDeposits($loan){
        $totalAmount = $loan->amount;
        $repayments = $loan->repayments;
        $max = $totalAmount - $repayments->sum('amount');
        $min = ($max) / ($loan->term_duration - $repayments->count());
        return ["max" => $max, "min" => $min];
    }
}
