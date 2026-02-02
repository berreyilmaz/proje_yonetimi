<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Finans extends Model
{
    protected $table = 'finans';

    protected $fillable = [
        'user_id',
        'company_id',
        'amount',
        'description',
        'date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}