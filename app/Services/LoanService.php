<?php
namespace App\Services;

use App\Models\Loan;
use App\Models\LoanStatuses;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class LoanService
{
    public function createLoan($userId, $amount, $termDuration)
    {
        $data = [
            'user_id' => $userId,
            'amount' => $amount,
            'term_duration' => $termDuration,
            'status_id' => LoanStatuses::PENDING
        ];

        $loan = Loan::create($data);

        return $loan;
    }

    public function getLoansForUser($userId)
    {
        $loans = Loan::where('user_id', $userId)->get();

        return $loans;
    }

    public function getLoanById($loanId, $userId)
    {
        $loan = Loan::with('repayments')->where([
            'id' => $loanId,
            'user_id' => $userId
        ])->first();

        return $loan;
    }

    public function approveLoan($loanId)
    {
        $loan = Loan::findOrFail($loanId);
        if ($loan->status_id != LoanStatuses::PENDING) {
            throw new HttpResponseException(
                response()->json(['message' => 'Invalid loan status'], Response::HTTP_UNPROCESSABLE_ENTITY)
            );
        }

        $loan->status_id = LoanStatuses::APPROVED;
        $loan->save();

        return $loan;
    }

    public function getAllLoans()
    {
        $loans = Loan::all();

        return $loans;
    }
}
