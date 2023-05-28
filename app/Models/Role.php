<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    const ADMIN = 1;
    const USER = 2;
    protected $fillable = [
        'name',
    ];

    // Relationships

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}