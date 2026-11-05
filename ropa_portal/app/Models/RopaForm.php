<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RopaForm extends Model
{
    protected $fillable = [
        'user_id',
        'college_id',
        'business_function',
        'main_process_name',
        'has_sub_processes',
        'all_submissions_completed',
        'firstname',
        'surname',
        'personnel_id',
        'role_responsible',
        // New metadata / governance
        'ropa_id',
        'version',
        'last_reviewed',
        'next_review_due',
        'processing_owner',
        'ropa_coordinator',
        'legal_institutional_authority',
        'act843_legal_justification',
        'other_applicable_law',
        'consent_used',
        'consent_evidence_reference',
        'notice_transparency_mechanism',
        'risk_rating',
        'remediation_actions',
        'remediation_owner',
        'processing_owner_signed_at',
        'privacy_office_validated_at',
        'approved_at',
        'comments_exceptions',
    ];

    protected $casts = [
        'has_sub_processes' => 'boolean',
        'all_submissions_completed' => 'boolean',
        'consent_used' => 'boolean',
        'last_reviewed' => 'date',
        'next_review_due' => 'date',
        'processing_owner_signed_at' => 'datetime',
        'privacy_office_validated_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (RopaForm $form) {
            if (empty($form->ropa_id)) {
                $form->ropa_id = 'UG-ROPA-' . str_pad(
                    (static::max('id') ?? 0) + 1,
                    4,
                    '0',
                    STR_PAD_LEFT
                );
            }
        });
    }

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
     * process name, personnel fields, and the has-sub-processes decision)
     * has been locked in on the very first submission.
     */
    public function basicInfoLocked(): bool
    {
        return $this->college_id !== null
            && $this->business_function !== ''
            && $this->main_process_name !== ''
            && ! empty($this->firstname)
            && ! empty($this->surname)
            && ! is_null($this->has_sub_processes);
    }
}
