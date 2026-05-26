<?php

namespace App\Http\Controllers;

use App\Models\RopaForm;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RopaFormController extends Controller
{
    /**
     * Display all forms for authenticated user
     */
    public function index(): View
    {
        $forms = RopaForm::where('user_id', Auth::id())
            ->latest('updated_at')
            ->get();

        return view('ropa.index', compact('forms'));
    }

    /**
     * Create new form draft
     */
    public function create(): RedirectResponse
    {
        $form = RopaForm::create([
            'user_id' => Auth::id(),
            'current_step' => 1,
            'status' => 'draft',
        ]);

        return redirect()->route('ropa.edit', $form);
    }

    /**
     * Edit form
     */
    public function edit(RopaForm $ropaForm): View
    {
        $this->authorizeForm($ropaForm);

        return view('ropa.form', compact('ropaForm'));
    }

    /**
     * Update form
     */
    public function update(Request $request, RopaForm $ropaForm): RedirectResponse
    {
        $this->authorizeForm($ropaForm);

        /**
         * Normalize boolean fields BEFORE validation
         */
        $request->merge([
            'share_internally' => $request->boolean('share_internally'),
            'lia_documented' => $request->boolean('lia_documented'),
            'legally_required_retention' => $request->boolean('legally_required_retention'),
            'auto_decision_making' => $request->boolean('auto_decision_making'),
            'dpia_required' => $request->boolean('dpia_required'),
            'breach_occurred' => $request->boolean('breach_occurred'),
            'retained_per_policy' => $request->boolean('retained_per_policy'),
        ]);

        /**
         * Convert JSON fields BEFORE validation
         */
        $jsonFields = [
            'process_names',
            'joint_controllers',
            'categories_records',
            'data_subjects',
            'personal_data_categories',
            'internal_recipients',
            'special_category_recipients',
            'legal_basis',
            'sensitive_legal_basis',
            'individual_rights',
            'external_recipients',
            'international_transfers',
            'transfer_mechanisms',
            'dpa_conditions',
            'gdpr_articles',
        ];

        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);

                if (is_string($value)) {

                    // Handle JSON strings
                    if (
                        str_starts_with(trim($value), '[') ||
                        str_starts_with(trim($value), '{')
                    ) {
                        $decoded = json_decode($value, true);

                        if (json_last_error() === JSON_ERROR_NONE) {
                            $request->merge([
                                $field => $decoded
                            ]);
                        } else {
                            $request->merge([
                                $field => !empty($value) ? [$value] : []
                            ]);
                        }
                    } else {
                        $request->merge([
                            $field => !empty($value) ? [$value] : []
                        ]);
                    }
                }
            }
        }

        /**
         * Ensure checkbox arrays always exist
         */
        $checkboxFields = [
            'categories_records',
            'individual_rights',
            'dpa_conditions',
            'gdpr_articles',
        ];

        foreach ($checkboxFields as $field) {
            if (!$request->has($field)) {
                $request->merge([
                    $field => []
                ]);
            }
        }

        // Handle share_internally specifically (convert to boolean)
        if ($request->has('share_internally')) {
            $validated['share_internally'] = (bool) $request->input('share_internally');
        }

        /**
         * Current step
         */
        $step = (int) $request->input('current_step', 1);

        /**
         * Validate
         */
        $validated = $request->validate(
            $this->getValidationRules($step)
        );

        /**
         * Determine next step
         */
        $newStep = $step;

        switch ($request->input('action')) {

            case 'next':
                $newStep = min($step + 1, 14);
                break;

            case 'previous':
                $newStep = max($step - 1, 1);
                break;
        }

        /**
         * Build update payload
         */
        $updateData = array_merge($validated, [
            'current_step' => $newStep,
        ]);

        /**
         * Handle submission
         */
        if ($request->input('action') === 'submit') {

            $updateData['status'] = 'submitted';
            $updateData['submitted_at'] = now();
        }

        /**
         * Save in transaction
         */
        DB::transaction(function () use ($ropaForm, $updateData) {
            $ropaForm->update($updateData);
        });

        /**
         * Redirect after submission
         */
        if ($request->input('action') === 'submit') {

            return redirect()
                ->route('ropa.index')
                ->with(
                    'success',
                    'RoPA form submitted successfully!'
                );
        }

        return redirect()
            ->route('ropa.edit', $ropaForm)
            ->with(
                'success',
                'Step ' . $newStep . ' saved successfully!'
            );
    }

    /**
     * Show completed form
     */
    public function show(RopaForm $ropaForm): View
    {
        $this->authorizeForm($ropaForm);

        return view('ropa.show', compact('ropaForm'));
    }

    /**
     * Delete form
     */
    public function destroy(RopaForm $ropaForm): RedirectResponse
    {
        $this->authorizeForm($ropaForm);

        $ropaForm->delete();

        return redirect()
            ->route('ropa.index')
            ->with(
                'success',
                'Form deleted successfully.'
            );
    }

    /**
     * Authorize ownership
     */
    private function authorizeForm(RopaForm $ropaForm): void
    {
        if ($ropaForm->user_id !== Auth::id()) {
            abort(403);
        }
    }

    /**
     * Validation rules by step
     */
    private function getValidationRules(int $step): array
    {
        $baseRules = [
            'current_step' => 'nullable|integer|min:1|max:14',
            'action' => 'nullable|string|in:next,previous,save,submit',
        ];

        $stepRules = [

            1 => [
                'personnel_id' => 'nullable|string|max:50',
                'surname' => 'nullable|string|max:100',
                'firstname' => 'nullable|string|max:100',
                'business_function' => 'nullable|string|max:255',
                'process_names' => 'nullable|array',
                'purpose' => 'nullable|string',
                'role_responsible' => 'nullable|string|max:255',
            ],

            2 => [
                'joint_controllers' => 'nullable|array',
            ],

            3 => [
                'categories_records' => 'nullable|array',
                'data_subjects' => 'nullable|array',
                'personal_data_categories' => 'nullable|array',
                'special_category_documents' => 'nullable|string',
            ],

            4 => [
                'internal_sharing_categories' => 'nullable|string',
                'share_internally' => 'nullable|boolean',
                'internal_recipients' => 'nullable|array',
                'special_category_recipients' => 'nullable|array',
                'sharing_reasons' => 'nullable|string',
            ],

            5 => [
                'data_source' => 'nullable|string|in:individual,third_party',
                'data_update_method' => 'nullable|string',
            ],

            6 => [
                'legal_basis' => 'nullable|array',
                'lia_documented' => 'nullable|boolean',
                'lia_location' => 'nullable|string|max:500',
                'sensitive_legal_basis' => 'nullable|array',
                'retention_period' => 'nullable|string|max:255',
                'legally_required_retention' => 'nullable|boolean',
                'special_category_condition' => 'nullable|string',
                'legitimate_interests' => 'nullable|string',
                'lia_link' => 'nullable|url|max:500',
                'individual_rights' => 'nullable|array',
            ],

            7 => [
                'security_measures' => 'nullable|string',
            ],

            8 => [
                'external_recipients' => 'nullable|array',
            ],

            9 => [
                'international_transfers' => 'nullable|array',
                'transfer_mechanisms' => 'nullable|array',
            ],

            10 => [
                'auto_decision_making' => 'nullable|boolean',
                'profiling_description' =>
                'nullable|string|required_if:auto_decision_making,true',
            ],

            11 => [
                'consent_link' => 'nullable|url|max:500',
                'data_location' => 'nullable|string',
            ],

            12 => [
                'dpia_required' => 'nullable|boolean',
                'dpia_progress' =>
                'nullable|string|in:not_started,in_progress,completed',
                'dpia_link' => 'nullable|url|max:500',
            ],

            13 => [
                'breach_occurred' => 'nullable|boolean',
                'breach_link' =>
                'nullable|url|max:500|required_if:breach_occurred,true',
            ],

            14 => [
                'dpa_conditions' => 'nullable|array',
                'gdpr_articles' => 'nullable|array',
                'retention_policy_link' => 'nullable|url|max:500',
                'retained_per_policy' => 'nullable|boolean',
                'retention_non_adherence_reason' =>
                'nullable|string|required_if:retained_per_policy,false',
            ],
        ];

        return array_merge(
            $baseRules,
            $stepRules[$step] ?? []
        );
    }
}
