<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['company_id', 'title', 'description', 'status', 'start_date', 'end_date','progress'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
