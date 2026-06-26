<?php

namespace App\Http\Controllers;

use App\Models\College;
use App\Models\RopaForm;
use App\Models\RopaSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RopaFormController extends Controller
{
    /**
     * Display all forms for authenticated user (with accordion of submissions)
     */
    public function index(): View
    {
        $forms = RopaForm::with(['college', 'submissions'])
            ->where('user_id', Auth::id())
            ->latest('updated_at')
            ->get();

        return view('ropa.index', compact('forms'));
    }

    /**
     * Create a new parent form and redirect to step 1
     */
    public function create(): RedirectResponse
    {
        session()->forget(['ropa_form_id', 'ropa_submission_id']);

        return redirect()->route('ropa.edit', ['step' => 1]);
    }

    /**
     * Edit a specific step of the current submission.
     * If no parent form or submission exists, create them on the fly.
     */
    public function edit($step = 1): View|RedirectResponse
    {
        $user = Auth::user();

        $parentForm = session('ropa_form_id') ? RopaForm::find(session('ropa_form_id')) : null;
        if (! $parentForm) {
            $parentForm = RopaForm::create([
                'user_id' => $user->id,
                'college_id' => null,
                'business_function' => '',
                'main_process_name' => '',
            ]);
            session(['ropa_form_id' => $parentForm->id]);
        }

        $submission = session('ropa_submission_id') ? RopaSubmission::find(session('ropa_submission_id')) : null;
        if (! $submission) {
            $submission = $parentForm->submissions()->create([
                'sub_process_name' => null,
                'current_step' => 1,
                'status' => 'draft',
            ]);
            session(['ropa_submission_id' => $submission->id]);
        }

        if ($submission->status === 'completed') {
            return redirect()->route('ropa.add-more', $parentForm);
        }

        if ($step < 1 || $step > 14) {
            $step = 1;
        }

        $colleges = College::all();

        $basicInfoLocked = $parentForm->basicInfoLocked();

        return view('ropa.form', compact('parentForm', 'submission', 'step', 'colleges', 'basicInfoLocked'));
    }

    /**
     * Update the current step of the submission.
     * Handles saving, navigation, and completion.
     */
    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $step = (int) $request->input('current_step', 1);

        $parentForm = session('ropa_form_id') ? RopaForm::find(session('ropa_form_id')) : null;
        if (! $parentForm) {
            return redirect()->route('ropa.create')->withErrors('Session expired. Please start again.');
        }

        $submission = session('ropa_submission_id') ? RopaSubmission::find(session('ropa_submission_id')) : null;
        if (! $submission) {
            return redirect()->route('ropa.edit', ['step' => 1])->withErrors('Submission not found.');
        }

        if ($request->input('action') === 'navigate') {
            return $this->handleNavigation($request, $parentForm, $submission);
        }

        // --- Step 1 specific handling ---
        if ($step === 1) {
            $validated = $request->validate([
                'college_id' => 'required|exists:colleges,id',
                'business_function' => 'required|string|max:255',
                'main_process_name' => 'required|string|max:255',
                'has_sub_processes' => 'nullable|boolean',
                'sub_process_name' => 'nullable|string|max:255|required_if:has_sub_processes,1',
                'personnel_id' => 'nullable|string|max:50',
                'surname' => 'required|string|max:100',
                'firstname' => 'required|string|max:100',
                'purpose' => 'nullable|string',
                'role_responsible' => 'nullable|string|max:255',
            ]);

            // Save has_sub_processes on the parent form
            $parentForm->update([
                'college_id' => $validated['college_id'],
                'business_function' => $validated['business_function'],
                'main_process_name' => $validated['main_process_name'],
                'has_sub_processes' => $validated['has_sub_processes'] ?? false,
            ]);

            $submission->update([
                'sub_process_name' => ($validated['has_sub_processes'] ?? false) ? $validated['sub_process_name'] : null,
                'personnel_id' => $validated['personnel_id'],
                'surname' => $validated['surname'],
                'firstname' => $validated['firstname'],
                'purpose' => $validated['purpose'],
                'role_responsible' => $validated['role_responsible'],
                'current_step' => 2,
            ]);

            return redirect()->route('ropa.edit', ['step' => 2])
                ->with('success', 'Step 1 saved. Proceed to step 2.');
        }

        // --- Steps 2-14 ---
        $this->normalizeRequest($request);
        $validated = $request->validate($this->getValidationRules($step));

        $newStep = $step;
        $action = $request->input('action');

        if ($action === 'next') {
            $newStep = min($step + 1, 14);
        } elseif ($action === 'previous') {
            $newStep = max($step - 1, 1);
        }

        $updateData = array_merge($validated, ['current_step' => $newStep]);

        if ($action === 'submit' && $step === 14) {
            $updateData['status'] = 'completed';
            $updateData['completed_at'] = now();
        }

        DB::transaction(function () use ($submission, $updateData) {
            $submission->update($updateData);
        });

        // Handle post‑submission decisions
        if ($action === 'submit' && $step === 14) {
            $nextAction = $request->input('next_action', 'add_more');

            return $this->handleSubProcessCompletion($parentForm, $nextAction);
        }

        return redirect()->route('ropa.edit', ['step' => $newStep])
            ->with('success', 'Step '.$newStep.' saved successfully.');
    }

    /**
     * Handle what happens after a sub-process is completed.
     */
    private function handleSubProcessCompletion(RopaForm $parentForm, string $nextAction): RedirectResponse
    {
        if ($nextAction === 'add_more') {
            return $this->handleAddAnotherSubProcess($parentForm);
        }

        // Default: finalize
        return $this->handleFinalize($parentForm);
    }

    /**
     * Add another sub-process.
     */
    private function handleAddAnotherSubProcess(RopaForm $parentForm): RedirectResponse
    {
        session()->forget('ropa_submission_id');
        $submission = $parentForm->submissions()->create([
            'sub_process_name' => null,
            'current_step' => 1,
            'status' => 'draft',
        ]);
        session(['ropa_submission_id' => $submission->id]);

        return redirect()->route('ropa.edit', ['step' => 1])
            ->with('info', 'Now fill in details for the next sub-process.');
    }

    /**
     * Finalize the entire form (all sub-processes completed).
     */
    private function handleFinalize(RopaForm $parentForm): RedirectResponse
    {
        $parentForm->update(['all_submissions_completed' => true]);

        session()->forget(['ropa_form_id', 'ropa_submission_id']);

        return redirect()->route('ropa.index')
            ->with('success', 'All sub-processes submitted successfully! Your RoPA form is now complete.');
    }

    /**
     * Public route versions of the private helpers (for the accordion buttons).
     */
    public function addSubProcess(RopaForm $parentForm): RedirectResponse
    {
        if ($parentForm->user_id !== Auth::id()) {
            abort(403);
        }

        return $this->handleAddAnotherSubProcess($parentForm);
    }

    public function finalize(RopaForm $parentForm): RedirectResponse
    {
        if ($parentForm->user_id !== Auth::id()) {
            abort(403);
        }

        return $this->handleFinalize($parentForm);
    }

    /**
     * Show the "add more" view after a sub-process is completed.
     */
    public function addMore(RopaForm $parentForm): View|RedirectResponse
    {
        if ($parentForm->user_id !== Auth::id()) {
            abort(403);
        }

        $completedSubmissions = $parentForm->submissions()->where('status', 'completed')->get();
        if ($completedSubmissions->isEmpty()) {
            return redirect()->route('ropa.edit', ['step' => 1]);
        }

        $currentSubmission = $completedSubmissions->last();

        return view('ropa.add-more', compact('parentForm', 'currentSubmission', 'completedSubmissions'));
    }

    /**
     * View a single submission (for details).
     */
    public function viewSubmission(RopaSubmission $submission): View
    {
        if ($submission->ropaForm->user_id !== Auth::id()) {
            abort(403);
        }

        return view('ropa.view-submission', compact('submission'));
    }

    // --- Navigation Handler ---
    private function handleNavigation(Request $request, RopaForm $parentForm, RopaSubmission $submission)
    {
        $targetStep = (int) $request->input('target_step');
        $currentStep = (int) $request->input('current_step', 1);

        try {
            $this->normalizeRequest($request);
            $validated = $request->validate($this->getValidationRules($currentStep));
            $submission->update(array_merge($validated, ['current_step' => $targetStep]));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('ropa.edit', ['step' => $targetStep]),
                    'message' => 'Navigated to step '.$targetStep,
                ]);
            }

            return redirect()->route('ropa.edit', ['step' => $targetStep])
                ->with('success', 'Navigated to step '.$targetStep);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
            }

            return back()->withErrors($e->getMessage());
        }
    }

    // --- Normalization ---
    private function normalizeRequest(Request $request)
    {
        $booleans = [
            'share_internally', 'lia_documented', 'legally_required_retention',
            'auto_decision_making', 'dpia_required', 'breach_occurred', 'retained_per_policy',
        ];
        foreach ($booleans as $field) {
            if ($request->has($field)) {
                $request->merge([$field => $request->boolean($field)]);
            }
        }

        $jsonFields = [
            'process_names', 'joint_controllers', 'categories_records', 'data_subjects',
            'personal_data_categories', 'internal_recipients', 'special_category_recipients',
            'legal_basis', 'sensitive_legal_basis', 'individual_rights', 'external_recipients',
            'international_transfers', 'transfer_mechanisms', 'dpa_conditions', 'gdpr_articles',
        ];
        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                if (is_string($value)) {
                    if (str_starts_with(trim($value), '[') || str_starts_with(trim($value), '{')) {
                        $decoded = json_decode($value, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $request->merge([$field => $decoded]);
                        } else {
                            $request->merge([$field => ! empty($value) ? [$value] : []]);
                        }
                    } else {
                        $request->merge([$field => ! empty($value) ? [$value] : []]);
                    }
                }
            }
        }

        $checkboxFields = [
            'categories_records', 'individual_rights', 'dpa_conditions',
            'gdpr_articles', 'legal_basis', 'sensitive_legal_basis',
        ];
        foreach ($checkboxFields as $field) {
            if (! $request->has($field) || $request->input($field) === null) {
                $request->merge([$field => []]);
            }
        }
    }

    // --- Validation Rules ---
    private function getValidationRules(int $step): array
    {
        $baseRules = [
            'current_step' => 'nullable|integer|min:1|max:14',
            'action' => 'nullable|string|in:next,previous,save,submit,navigate',
        ];

        $stepRules = [
            1 => [
                'personnel_id' => 'nullable|string|max:50',
                'surname' => 'required|string|max:100',
                'firstname' => 'required|string|max:100',
                'purpose' => 'nullable|string',
                'role_responsible' => 'nullable|string|max:255',
            ],
            2 => [
                'joint_controllers' => 'required|array|min:1',
            ],
            3 => [
                'categories_records' => 'required|array|min:1',
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
                'data_source' => 'required|string|in:individual,third_party',
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
                'external_recipients' => 'required|array|min:1',
            ],
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
                'dpa_conditions' => 'required|array|min:1',
                'gdpr_articles' => 'required|array|min:1',
                'retention_policy_link' => 'required|url|max:500',
                'retained_per_policy' => 'required|boolean',
                'retention_non_adherence_reason' => 'nullable|string|required_if:retained_per_policy,false',
            ],
        ];

        return array_merge($baseRules, $stepRules[$step] ?? []);
    }
}
