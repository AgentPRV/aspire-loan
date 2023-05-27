<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRepayment extends Model
{
    protected $fillable = ['amount', 'loan_id'];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}