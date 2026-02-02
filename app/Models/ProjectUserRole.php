<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectUserRole extends Model
{
    protected $fillable = ['project_id', 'user_id', 'role'];
}
