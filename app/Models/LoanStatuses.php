<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanStatuses extends Model
{
    const PENDING = 1;
    const APPROVED = 2;
    const PAID = 3;

    protected $fillable = [
        'name',
    ];

    // Relationships

    public function loans()
    {
        return $this->belongsToMany(Loans::class);
    }
}