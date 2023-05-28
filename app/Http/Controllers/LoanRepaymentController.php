<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanStatuses;
use App\Services\LoanRepaymentService;
use Illuminate\Http\Request;

class LoanRepaymentController extends Controller
{
    private $loanRepaymentService;

    public function __construct(LoanRepaymentService $loanRepaymentService)
    {
        $this->loanRepaymentService = $loanRepaymentService;
    }

    public function create(Request $request, $loanId)
    {
        $request->validate([
            'amount' => 'required|numeric',
        ]);

        $loan = Loan::with('repayments')->where([
            'id' => $loanId,
            'user_id' => $request->user()->id
        ])->first();

        if (!$loan || $loan->status_id != LoanStatuses::APPROVED) {
            return response(["message"=> "Invalid loan ID/Status"], 422);
        }

        try {
            $repayment = $this->loanRepaymentService->createRepayment($loan, $request->input('amount'));
        } catch (\InvalidArgumentException $e) {
            return response(["message" => $e->getMessage()], 422);
        }

        return response()->json($repayment, 201);
    }
}
