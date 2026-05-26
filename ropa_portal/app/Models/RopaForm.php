<?php
// File: app/Models/RopaForm.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RopaForm extends Model
{
    protected $fillable = [
        'user_id', 'personnel_id', 'surname', 'firstname', 'business_function', 'process_names',
        'purpose', 'role_responsible', 'joint_controllers', 'categories_records', 'data_subjects',
        'personal_data_categories', 'special_category_documents', 'internal_sharing_categories',
        'share_internally', 'internal_recipients', 'special_category_recipients', 'sharing_reasons',
        'data_source', 'data_update_method', 'legal_basis', 'lia_documented', 'lia_location',
        'sensitive_legal_basis', 'retention_period', 'legally_required_retention', 'special_category_condition',
        'legitimate_interests', 'lia_link', 'individual_rights', 'security_measures', 'external_recipients',
        'international_transfers', 'transfer_mechanisms', 'auto_decision_making', 'profiling_description',
        'consent_link', 'data_location', 'dpia_required', 'dpia_progress', 'dpia_link', 'breach_occurred',
        'breach_link', 'dpa_conditions', 'gdpr_articles', 'retention_policy_link', 'retained_per_policy',
        'retention_non_adherence_reason', 'current_step', 'status', 'submitted_at'
    ];

    protected $casts = [
        'process_names' => 'array',
        'joint_controllers' => 'array',
        'categories_records' => 'array',
        'data_subjects' => 'array',
        'personal_data_categories' => 'array',
        'internal_sharing_categories' => 'array',
        'internal_recipients' => 'array',
        'special_category_recipients' => 'array',
        'legal_basis' => 'array',
        'sensitive_legal_basis' => 'array',
        'individual_rights' => 'array',
        'external_recipients' => 'array',
        'international_transfers' => 'array',
        'transfer_mechanisms' => 'array',
        'dpa_conditions' => 'array',
        'gdpr_articles' => 'array',
        'share_internally' => 'boolean',
        'lia_documented' => 'boolean',
        'legally_required_retention' => 'boolean',
        'auto_decision_making' => 'boolean',
        'dpia_required' => 'boolean',
        'breach_occurred' => 'boolean',
        'retained_per_policy' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
