<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $fillable = [
        'user_id', 'name', 'address', 'city', 'state',
        'affiliation_no', 'contact_phone', 'website', 'type', 'status', 'rejection_reason',
    ];

    public function user()     { return $this->belongsTo(User::class); }
    public function students() { return $this->hasMany(Student::class); }

    public function isApproved(): bool { return $this->status === 'approved'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isRejected(): bool { return $this->status === 'rejected'; }
}
