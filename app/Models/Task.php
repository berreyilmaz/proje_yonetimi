<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Bu dizi içindeki alanların dışarıdan kaydedilmesine izin veriyoruz
    protected $fillable = [
        'title', 
        'description', 
        'status', 
        'company_id', 
        'project_id', 
        'assigned_to'
    ];

    // İlişkiler: Tabloda isimlerin görünmesi için bunları da ekle
    public function project() {
        return $this->belongsTo(Project::class);
    }

    public function assignedUser() {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
