<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'university_id', 'name', 'roll_no', 'email', 'course', 'department',
        'year', 'cgpa', 'status', 'admission_year', 'passout_year', 'gender', 'state_of_origin',
    ];

    public function university() { return $this->belongsTo(University::class); }
}
