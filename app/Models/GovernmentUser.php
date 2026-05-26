<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GovernmentUser extends Model
{
    protected $fillable = [
        'user_id', 'department', 'designation', 'ministry', 'employee_id',
    ];

    public function user() { return $this->belongsTo(User::class); }
}
