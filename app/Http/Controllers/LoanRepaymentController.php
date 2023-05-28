<?php

namespace App\Http\Controllers;

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
        $userId = $request->user()->id;
        try {
            $repayment = $this->loanRepaymentService->createRepayment($loanId, $request->input('amount'), $userId);
        } catch (\InvalidArgumentException $e) {
            return response(["message" => $e->getMessage()], 422);
        }

        return response()->json($repayment, 201);
    }
}
