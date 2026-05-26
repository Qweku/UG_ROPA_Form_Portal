<?php

namespace App\Http\Controllers;

use App\Models\RopaForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RopaFormController extends Controller
{
    public function index()
    {
        $forms = RopaForm::where('user_id', Auth::id())->orderBy('updated_at', 'desc')->get();
        return view('ropa.index', compact('forms'));
    }

    public function create()
    {
        $form = RopaForm::create(['user_id' => Auth::id(), 'current_step' => 1]);
        return redirect()->route('ropa.edit', $form);
    }

    public function edit(RopaForm $ropaForm)
    {
        if ($ropaForm->user_id !== Auth::id()) abort(403);
        return view('ropa.form', compact('ropaForm'));
    }



    public function update(Request $request, RopaForm $ropaForm)
    {
        if ($ropaForm->user_id !== Auth::id()) abort(403);


         // Quick fix for process_names
        if ($request->has('process_names') && is_string($request->process_names)) {
            $decoded = json_decode($request->process_names, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $request->merge(['process_names' => $decoded]);
            } else {
                $request->merge(['process_names' => []]);
            }
        }


        $step = $request->input('current_step', 1);
        $rules = $this->getValidationRules($step);
        $validated = $request->validate($rules);

        // Handle JSON fields - SINGLE SOURCE OF TRUTH
        $jsonFields = [
            'process_names',
            'joint_controllers',
            'categories_records',
            'data_subjects',
            'personal_data_categories',
            'internal_sharing_categories',
            'internal_recipients',
            'special_category_recipients',
            'legal_basis',
            'sensitive_legal_basis',
            'individual_rights',
            'external_recipients',
            'international_transfers',
            'transfer_mechanisms',
            'dpa_conditions',
            'gdpr_articles'
        ];

        foreach ($jsonFields as $jsonField) {
            if ($request->has($jsonField)) {
                $value = $request->input($jsonField);

                // If it's a string, try to decode it as JSON
                if (is_string($value)) {
                    $decoded = json_decode($value, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                        $validated[$jsonField] = $decoded;
                    } elseif ($value === '[]' || empty($value)) {
                        $validated[$jsonField] = [];
                    } else {
                        // If not JSON, treat as single value
                        $validated[$jsonField] = $value ? [$value] : [];
                    }
                } elseif (is_array($value)) {
                    $validated[$jsonField] = $value;
                } else {
                    $validated[$jsonField] = [];
                }
            }
        }

        // FIXED: Remove the duplicate quick fix since it's now handled in the loop above
        // The old quick fix code has been removed to prevent duplication



        $ropaForm->update(array_merge($validated, ['current_step' => $step]));

        if ($request->input('action') === 'next') {
            $nextStep = min($step + 1, 14);
            $ropaForm->update(['current_step' => $nextStep]);
        } elseif ($request->input('action') === 'previous') {
            $prevStep = max($step - 1, 1);
            $ropaForm->update(['current_step' => $prevStep]);
        } elseif ($request->input('action') === 'submit') {
            $ropaForm->update(['status' => 'submitted', 'submitted_at' => now()]);
            return redirect()->route('ropa.show', $ropaForm)->with('success', 'RoPA form submitted successfully!');
        }

        return redirect()->route('ropa.edit', $ropaForm)->with('success', 'Draft saved successfully!');
    }

    public function show(RopaForm $ropaForm)
    {
        if ($ropaForm->user_id !== Auth::id()) abort(403);
        return view('ropa.show', compact('ropaForm'));
    }

    public function destroy(RopaForm $ropaForm)
    {
        if ($ropaForm->user_id !== Auth::id()) abort(403);
        $ropaForm->delete();
        return redirect()->route('ropa.index')->with('success', 'Form deleted.');
    }

    private function getValidationRules(int $step): array
    {
        $baseRules = [
            'current_step' => 'integer|min:1|max:14',
            'action' => 'string|in:next,previous,save,submit',
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
            2 => ['joint_controllers' => 'nullable|array'],
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
            7 => ['security_measures' => 'nullable|string'],
            8 => ['external_recipients' => 'nullable|array'],
            9 => [
                'international_transfers' => 'nullable|array',
                'transfer_mechanisms' => 'nullable|array',
            ],
            10 => [
                'auto_decision_making' => 'nullable|boolean',
                'profiling_description' => 'nullable|string|required_if:auto_decision_making,true',
            ],
            11 => [
                'consent_link' => 'nullable|url|max:500',
                'data_location' => 'nullable|string',
            ],
            12 => [
                'dpia_required' => 'nullable|boolean',
                'dpia_progress' => 'nullable|string|in:not_started,in_progress,completed',
                'dpia_link' => 'nullable|url|max:500',
            ],
            13 => [
                'breach_occurred' => 'nullable|boolean',
                'breach_link' => 'nullable|url|max:500|required_if:breach_occurred,true',
            ],
            14 => [
                'dpa_conditions' => 'nullable|array',
                'gdpr_articles' => 'nullable|array',
                'retention_policy_link' => 'nullable|url|max:500',
                'retained_per_policy' => 'nullable|boolean',
                'retention_non_adherence_reason' => 'nullable|string|required_if:retained_per_policy,false',
            ],
        ];

        return array_merge($baseRules, $stepRules[$step] ?? []);
    }
}
