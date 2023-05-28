<?php

namespace App\Http\Controllers;

use App\Services\LoanService;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    private $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'term_duration' => 'required|integer|min:1'
        ]);

        $userId = $request->user()->id;
        $amount = $request->amount;
        $termDuration = $request->term_duration;

        $loan = $this->loanService->createLoan($userId, $amount, $termDuration);

        return response()->json($loan, 201);
    }

    public function getAllForUser(Request $request)
    {
        $userId = $request->user()->id;
        $loans = $this->loanService->getLoansForUser($userId);

        return response()->json($loans);
    }

    public function getById(Request $request, $id)
    {
        $userId = $request->user()->id;
        $loan = $this->loanService->getLoanById($id, $userId);

        return response()->json($loan);
    }

    public function approve(Request $request, $loanId)
    {
        $loan = $this->loanService->approveLoan($loanId);

        return response()->json($loan);
    }

    public function getAll(Request $request)
    {
        $loans = $this->loanService->getAllLoans();

        return response()->json($loans);
    }
}
