<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RopaForm extends Model
{
    protected $fillable = [
        'user_id', 'college_id', 'business_function', 'main_process_name',
        'has_sub_processes', 'all_submissions_completed',
    ];

    protected $casts = [
        'has_sub_processes' => 'boolean',
        'all_submissions_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function submissions()
    {
        return $this->hasMany(RopaSubmission::class);
    }

    /**
     * True once the basic process info (college, business function, main
     * process name, and the has-sub-processes decision) has been locked in
     * on the very first submission.
     */
    public function basicInfoLocked(): bool
    {
        return $this->college_id !== null
            && $this->business_function !== ''
            && $this->main_process_name !== ''
            && ! is_null($this->has_sub_processes);
    }
}
