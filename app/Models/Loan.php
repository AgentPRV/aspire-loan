<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'term_duration',
        'status_id',
    ];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function status()
    {
        return $this->belongsTo(LoanStatuses::class);
    }

    public function repayments(){
        return $this->hasMany(LoanRepayment::class);
    }
}