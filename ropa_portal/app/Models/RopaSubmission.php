<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RopaSubmission extends Model
{
    protected $fillable = [
        'ropa_form_id', 'sub_process_name',
        'purpose',
        'joint_controllers', 'categories_records', 'data_subjects', 'personal_data_categories',
        'special_category_documents', 'internal_sharing_categories', 'share_internally',
        'internal_recipients', 'special_category_recipients', 'sharing_reasons',
        'data_source', 'data_update_method', 'legal_basis', 'lia_documented',
        'lia_location', 'sensitive_legal_basis', 'retention_period',
        'legally_required_retention', 'special_category_condition', 'legitimate_interests',
        'lia_link', 'individual_rights', 'security_measures', 'external_recipients',
        'international_transfers', 'transfer_mechanisms', 'auto_decision_making',
        'profiling_description', 'consent_link', 'data_location', 'dpia_required',
        'dpia_progress', 'dpia_link', 'breach_occurred', 'breach_link',
        'dpa_conditions', 'gdpr_articles', 'cybersecurity_articles', 'other_articles',
        'retention_policy_link',
        'retained_per_policy', 'retention_non_adherence_reason',
        'current_step', 'status', 'completed_at',
    ];

    protected $casts = [
        'joint_controllers' => 'array',
        'categories_records' => 'array',
        'data_subjects' => 'array',
        'personal_data_categories' => 'array',
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
        'cybersecurity_articles' => 'array',
        'other_articles' => 'array',
        'share_internally' => 'boolean',
        'lia_documented' => 'boolean',
        'legally_required_retention' => 'boolean',
        'auto_decision_making' => 'boolean',
        'dpia_required' => 'boolean',
        'breach_occurred' => 'boolean',
        'retained_per_policy' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function ropaForm()
    {
        return $this->belongsTo(RopaForm::class);
    }

    /**
     * Grouped label => value pairs for read-only display (accordion panels,
     * submission detail view). Grouped roughly by wizard step so the
     * accordion body mirrors the order the user filled things in.
     *
     * Empty/null values are omitted so panels stay compact.
     */
    public function displayFields(): array
    {
        $parentForm = $this->ropaForm;

        $groups = [
            'Process Identity' => [
                'Sub-process Name' => $this->sub_process_name,
                'Personnel ID' => $parentForm->personnel_id,
                'Surname' => $parentForm->surname,
                'Firstname' => $parentForm->firstname,
                'Purpose of Processing' => $this->purpose,
                'Role Responsible' => $parentForm->role_responsible,
            ],
            'Joint Controllers' => [
                'Joint Controllers' => $this->formatArray($this->joint_controllers),
            ],
            'Data Categories' => [
                'Categories of Records' => $this->formatArray($this->categories_records),
                'Data Subjects' => $this->formatArray($this->data_subjects),
                'Personal Data Categories' => $this->formatArray($this->personal_data_categories),
                'Special Category Documents' => $this->special_category_documents,
            ],
            'Internal Sharing' => [
                'Internal Sharing Categories' => $this->internal_sharing_categories,
                'Shared Internally?' => $this->formatBool($this->share_internally),
                'Internal Recipients' => $this->formatArray($this->internal_recipients),
                'Special Category Recipients' => $this->formatArray($this->special_category_recipients),
                'Sharing Reasons' => $this->sharing_reasons,
            ],
            'Data Source' => [
                'Data Source' => $this->data_source,
                'Data Update Method' => $this->data_update_method,
            ],
            'Legal Basis & Retention' => [
                'Legal Basis' => $this->formatArray($this->legal_basis),
                'LIA Documented?' => $this->formatBool($this->lia_documented),
                'LIA Location' => $this->lia_location,
                'Sensitive Legal Basis' => $this->formatArray($this->sensitive_legal_basis),
                'Retention Period' => $this->retention_period,
                'Legally Required Retention?' => $this->formatBool($this->legally_required_retention),
                'Special Category Condition' => $this->special_category_condition,
                'Legitimate Interests' => $this->legitimate_interests,
                'LIA Link' => $this->lia_link,
                'Individual Rights' => $this->formatArray($this->individual_rights),
            ],
            'Security' => [
                'Security Measures' => $this->security_measures,
            ],
            'External Sharing' => [
                'External Recipients' => $this->formatArray($this->external_recipients),
            ],
            'International Transfers' => [
                'International Transfers' => $this->formatArray($this->international_transfers),
                'Transfer Mechanisms' => $this->formatArray($this->transfer_mechanisms),
            ],
            'Automated Decision-Making' => [
                'Auto Decision-Making?' => $this->formatBool($this->auto_decision_making),
                'Profiling Description' => $this->profiling_description,
            ],
            'Consent' => [
                'Consent Link' => $this->consent_link,
                'Data Location' => $this->data_location,
            ],
            'DPIA' => [
                'DPIA Required?' => $this->formatBool($this->dpia_required),
                'DPIA Progress' => $this->dpia_progress,
                'DPIA Link' => $this->dpia_link,
            ],
            'Breaches' => [
                'Breach Occurred?' => $this->formatBool($this->breach_occurred),
                'Breach Link' => $this->breach_link,
            ],
            'Compliance' => [
                'DPA Conditions' => $this->formatArray($this->dpa_conditions),
                'GDPR Articles' => $this->formatArray($this->gdpr_articles),
                'Cybersecurity Articles' => $this->formatArray($this->cybersecurity_articles),
                'Other Articles' => $this->formatArray($this->other_articles),
                'Retention Policy Link' => $this->retention_policy_link,
                'Retained Per Policy?' => $this->formatBool($this->retained_per_policy),
                'Retention Non-Adherence Reason' => $this->retention_non_adherence_reason,
            ],
        ];

        // Drop empty values within each group, then drop empty groups.
        $groups = array_map(fn ($fields) => array_filter($fields, fn ($v) => ! is_null($v) && $v !== ''), $groups);

        return array_filter($groups, fn ($fields) => count($fields) > 0);
    }

    private function formatArray($value): ?string
    {
        if (empty($value) || ! is_array($value)) {
            return null;
        }

        return implode(', ', $value);
    }

    private function formatBool($value): ?string
    {
        if (is_null($value)) {
            return null;
        }

        return $value ? 'Yes' : 'No';
    }

    public function getDpiaRiskBadgeAttribute(): ?array
    {
        if ($this->dpia_progress === 'completed') {
            return ['level' => 'Risk Managed', 'class' => 'bg-success', 'icon' => 'check'];
        }

        if ($this->dpia_progress === 'in_progress') {
            return ['level' => 'Mitigation in Progress', 'class' => 'bg-warning', 'icon' => 'clock'];
        }

        if ($this->dpia_required) {
            return ['level' => 'High - Assessment Required', 'class' => 'bg-danger', 'icon' => 'exclamation'];
        }

        return ['level' => 'Not Assessed', 'class' => 'bg-secondary', 'icon' => 'exclamation'];
    }
}
