<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialTransaction extends Model
{
    protected $fillable = [
        'company_id', 'project_id', 'reference_id', 
        'reference_type', 'title', 'amount', 'type', 'date', 'notes'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function referencable()
    {
        return $this->morphTo('reference');
    }
}