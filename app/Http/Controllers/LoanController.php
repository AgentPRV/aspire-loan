<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\LoanStatuses;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'term_duration' => 'required|integer'
        ]);

        $data = [
            'user_id' => $request->user()->id,
            'amount' => $request->amount,
            'term_duration' => $request->term_duration,
            'status_id' => LoanStatuses::PENDING
        ];

        $loan = Loan::create($data);

        return response()->json($loan, 201);
    }

    public function getAllForUser(Request $request)
    {   
        $loans = Loan::where('user_id', $request->user()->id)->get();

        return response()->json($loans);
    }

    public function getById(Request $request, $id)
    {
        $loan = Loan::with('repayments')->where([
            'id' => $id,
            'user_id' => $request->user()->id
        ])->first();

        return response()->json($loan);
    }

    public function approve(Request $request, $loanId)
    {
        $loan = Loan::findOrFail($loanId);
        if($loan->status_id != LoanStatuses::PENDING){
            return response(["message"=> "Invalid loan status"], 412);
        }
        $loan->status_id = LoanStatuses::APPROVED;
        $loan->save();

        return response()->json($loan);
    }

    public function getAll(Request $request)
    {   
        $loans = Loan::all();

        return response()->json($loans);
    }

    
}
