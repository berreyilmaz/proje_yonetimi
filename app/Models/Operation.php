<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Operation extends Model
{
    protected $fillable = [
        'project_id', 'user_id', 'type', 'impact_value', 
        'description', 'status', 'approved_by'
    ];

    // Operasyonun ait olduğu proje
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // Talebi oluşturan kullanıcı
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Onaylayan yönetici
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}