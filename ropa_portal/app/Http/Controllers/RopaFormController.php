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
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RopaFormController extends Controller
{
    private function canAccess(RopaForm $parentForm): bool
    {
        $user = Auth::user();

        return $user && ($parentForm->user_id === $user->id || $user->isAdmin());
    }

    private function isAdminUser(): bool
    {
        $user = Auth::user();

        return $user && $user->isAdmin();
    }

    private function resolveAuthorizedForm(?RopaForm $parentForm = null): ?RopaForm
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }

        $candidate = $parentForm ?: (session('ropa_form_id') ? RopaForm::with('college')->find(session('ropa_form_id')) : null);

        if ($candidate && $this->canAccess($candidate)) {
            return $candidate;
        }

        if ($user->isAdmin()) {
            return $candidate;
        }

        return RopaForm::with('college')
            ->where('user_id', $user->id)
            ->where('all_submissions_completed', false)
            ->latest('updated_at')
            ->first();
    }

    private function resolveAuthorizedSubmission(RopaForm $parentForm, ?RopaSubmission $submission = null): ?RopaSubmission
    {
        $candidate = $submission ?: (session('ropa_submission_id') ? RopaSubmission::find(session('ropa_submission_id')) : null);

        if ($candidate && $candidate->ropa_form_id === $parentForm->id) {
            return $candidate;
        }

        return $parentForm->submissions()
            ->where('status', 'draft')
            ->latest('updated_at')
            ->first();
    }

    public function index(): View
    {
        $forms = RopaForm::with(['college', 'submissions'])
            ->where('user_id', Auth::id())
            ->latest('updated_at')
            ->get();

        return view('ropa.index', compact('forms'));
    }

    public function create(): RedirectResponse
    {
        $user = Auth::user();

        $existingForm = RopaForm::where('user_id', $user->id)
            ->where('all_submissions_completed', false)
            ->latest('updated_at')
            ->first();

        if ($existingForm) {
            return $this->resumeForm($existingForm);
        }

        session()->forget(['ropa_form_id', 'ropa_submission_id']);

        $parentForm = RopaForm::create([
            'user_id' => $user->id,
            'college_id' => null,
            'business_function' => '',
            'main_process_name' => '',
        ]);

        $submission = $parentForm->submissions()->create([
            'sub_process_name' => null,
            'current_step' => 1,
            'status' => 'draft',
        ]);

        session([
            'ropa_form_id' => $parentForm->id,
            'ropa_submission_id' => $submission->id,
        ]);

        return redirect()->route('ropa.edit', ['step' => 1]);
    }

    private function resumeForm(RopaForm $parentForm): RedirectResponse
    {
        $draftSubmission = $parentForm->submissions()
            ->where('status', 'draft')
            ->latest('updated_at')
            ->first();

        if (! $draftSubmission) {
            session(['ropa_form_id' => $parentForm->id]);
            session()->forget('ropa_submission_id');

            $processLabel = $parentForm->main_process_name
                ? ' ("'.$parentForm->main_process_name.'")'
                : '';

            return redirect()->route('ropa.add-more', $parentForm)
                ->with('info', 'You have an unfinished RoPA process'.$processLabel.' — continuing where you left off. Finish or submit it before starting a new one.');
        }

        session([
            'ropa_form_id' => $parentForm->id,
            'ropa_submission_id' => $draftSubmission->id,
        ]);

        return redirect()->route('ropa.edit', ['step' => $draftSubmission->current_step])
            ->with('info', 'You have an unfinished RoPA process in progress — continuing where you left off. Finish or submit it before starting a new one.');
    }

    public function edit($step = 1): View|RedirectResponse
    {
        $user = Auth::user();
        $isAdmin = $this->isAdminUser();

        $parentForm = session('ropa_form_id') ? RopaForm::with('college')->find(session('ropa_form_id')) : null;

        if ($isAdmin && session('ropa_form_id') && ! $parentForm) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Selected form not found.');
        }

        if ($isAdmin && ! session('ropa_form_id')) {
            return redirect()->route('admin.dashboard')
                ->with('info', 'Please select a form from the admin dashboard to edit steps 12 and 13.');
        }

        if (! $parentForm || ! $this->canAccess($parentForm)) {
            $parentForm = RopaForm::with('college')
                ->where('user_id', $user->id)
                ->where('all_submissions_completed', false)
                ->latest('updated_at')
                ->first();
        }

        if (! $parentForm) {
            $parentForm = RopaForm::create([
                'user_id' => $user->id,
                'college_id' => null,
                'business_function' => '',
                'main_process_name' => '',
            ]);
        }
        session(['ropa_form_id' => $parentForm->id]);

        $submission = session('ropa_submission_id') ? RopaSubmission::find(session('ropa_submission_id')) : null;

        if (! $submission || $submission->ropa_form_id !== $parentForm->id) {
            $submission = $parentForm->submissions()
                ->where('status', 'draft')
                ->latest('updated_at')
                ->first();
        }

        if (! $submission) {
            $submission = $parentForm->submissions()->create([
                'sub_process_name' => null,
                'current_step' => 1,
                'status' => 'draft',
            ]);
        }
        session(['ropa_submission_id' => $submission->id]);

        if ($submission->status === 'completed') {
            return redirect()->route('ropa.add-more', $parentForm);
        }

        if ($step < 1 || $step > 14) {
            $step = 1;
        }

        if (! $isAdmin && in_array($step, [12, 13], true)) {
            return redirect()->route('ropa.edit', ['step' => 14])
                ->with('info', 'Steps 12 and 13 are restricted to admin users.');
        }

        $colleges = College::all();
        $basicInfoLocked = $parentForm->basicInfoLocked();
        $displayStep = $this->getDisplayStep($step, $isAdmin);

        return view('ropa.form', compact('parentForm', 'submission', 'step', 'colleges', 'basicInfoLocked', 'isAdmin', 'displayStep'));
    }

    private function getDisplayStep(int $step, bool $isAdmin): int
    {
        if ($isAdmin || $step < 12) {
            return $step;
        }

        return 12;
    }

    private function getNextStep(int $step, bool $isAdmin): int
    {
        $next = $step + 1;
        if (! $isAdmin && $next === 12 && $step < 14) {
            return 14;
        }

        return min($next, 14);
    }

    private function getPreviousStep(int $step, bool $isAdmin): int
    {
        $prev = $step - 1;
        if (! $isAdmin && $step === 14) {
            return 11;
        }

        return max($prev, 1);
    }

    public function update(Request $request): RedirectResponse|JsonResponse
    {
        $step = (int) $request->input('current_step', 1);

        $parentForm = $this->resolveAuthorizedForm();
        if (! $parentForm || ! $this->canAccess($parentForm)) {
            return redirect()->route('ropa.create')->withErrors('Session expired. Please start again.');
        }
        session(['ropa_form_id' => $parentForm->id]);

        $submission = $this->resolveAuthorizedSubmission($parentForm);
        if (! $submission) {
            return redirect()->route('ropa.edit', ['step' => 1])->withErrors('Submission not found.');
        }
        session(['ropa_submission_id' => $submission->id]);

        if ($request->input('action') === 'navigate') {
            return $this->handleNavigation($request, $parentForm, $submission);
        }

        $isAdmin = $this->isAdminUser();
        if (! $isAdmin && in_array($step, [12, 13], true)) {
            abort(403);
        }

        if ($step === 1) {
            $basicInfoLocked = $parentForm->basicInfoLocked();

            $validated = $request->validate([
                'college_id' => 'required|exists:colleges,id',
                'business_function' => 'required|string|max:255',
                'main_process_name' => 'required|string|max:255',
                'has_sub_processes' => 'nullable|boolean',
                'sub_process_name' => 'nullable|string|max:255|required_if:has_sub_processes,1',
                'personnel_id' => $basicInfoLocked ? 'sometimes|nullable|string|max:50' : 'nullable|string|max:50',
                'surname' => $basicInfoLocked ? 'sometimes|required|string|max:100' : 'required|string|max:100',
                'firstname' => $basicInfoLocked ? 'sometimes|required|string|max:100' : 'required|string|max:100',
                'purpose' => 'nullable|string',
                'role_responsible' => 'nullable|string|max:255',
                'processing_owner' => 'nullable|string|max:255',
                'ropa_coordinator' => 'nullable|string|max:255',
                'legal_institutional_authority' => 'nullable|string|max:500',
                'act843_legal_justification' => 'nullable|string',
                'other_applicable_law' => 'nullable|string',
                'consent_used' => 'nullable|boolean',
                'consent_evidence_reference' => 'nullable|string|max:500',
                'notice_transparency_mechanism' => 'nullable|string',
                'risk_rating' => 'nullable|string|in:Low,Moderate,High,Critical',
                'remediation_actions' => 'nullable|string',
                'remediation_owner' => 'nullable|string|max:255',
                'comments_exceptions' => 'nullable|string',
            ]);

            $parentFormUpdateData = [
                'college_id' => $validated['college_id'],
                'business_function' => $validated['business_function'],
                'main_process_name' => $validated['main_process_name'],
                'has_sub_processes' => $validated['has_sub_processes'] ?? 0,
                'firstname' => $validated['firstname'],
                'surname' => $validated['surname'],
                'personnel_id' => $validated['personnel_id'],
                'role_responsible' => $validated['role_responsible'],
                'processing_owner' => $validated['processing_owner'] ?? null,
                'ropa_coordinator' => $validated['ropa_coordinator'] ?? null,
                'legal_institutional_authority' => $validated['legal_institutional_authority'] ?? null,
                'act843_legal_justification' => $validated['act843_legal_justification'] ?? null,
                'other_applicable_law' => $validated['other_applicable_law'] ?? null,
                'consent_used' => $validated['consent_used'] ?? false,
                'consent_evidence_reference' => $validated['consent_evidence_reference'] ?? null,
                'notice_transparency_mechanism' => $validated['notice_transparency_mechanism'] ?? null,
                'risk_rating' => $validated['risk_rating'] ?? null,
                'remediation_actions' => $validated['remediation_actions'] ?? null,
                'remediation_owner' => $validated['remediation_owner'] ?? null,
                'comments_exceptions' => $validated['comments_exceptions'] ?? null,
            ];

            $parentForm->update($parentFormUpdateData);

            $submission->update([
                'sub_process_name' => ($parentForm->has_sub_processes ?? false) ? $validated['sub_process_name'] : null,
                'purpose' => $validated['purpose'],
                'current_step' => 2,
            ]);

            return redirect()->route('ropa.edit', ['step' => 2])
                ->with('success', 'Step 1 saved. Proceed to step 2.');
        }

        $this->normalizeRequest($request);

        $stepForValidation = $isAdmin ? $step : ($step >= 14 ? $step : ($step >= 12 ? 14 : $step));
        $validated = $request->validate($this->getValidationRules($stepForValidation));

        $newStep = $step;
        $action = $request->input('action');

        if ($action === 'next') {
            $newStep = $this->getNextStep($step, $isAdmin);
        } elseif ($action === 'previous') {
            $newStep = $this->getPreviousStep($step, $isAdmin);
        }

        $updateData = array_merge($validated, ['current_step' => $newStep]);
        $isFinalStep = $step === 14 || (! $isAdmin && $step >= 12);
        if ($action === 'submit' && $isFinalStep) {
            $updateData['status'] = 'completed';
            $updateData['completed_at'] = now();
        }

        DB::transaction(function () use ($submission, $updateData) {
            $submission->update($updateData);
        });

        if ($action === 'submit' && $isFinalStep) {
            $nextAction = $request->input('next_action', 'add_more');

            return $this->handleSubProcessCompletion($parentForm, $nextAction);
        }

        return redirect()->route('ropa.edit', ['step' => $newStep])
            ->with('success', 'Step '.$newStep.' saved successfully.');
    }

    private function handleSubProcessCompletion(RopaForm $parentForm, string $nextAction): RedirectResponse
    {
        if ($nextAction === 'add_more') {
            return $this->handleAddAnotherSubProcess($parentForm);
        }

        return $this->handleFinalize($parentForm);
    }

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

    private function handleFinalize(RopaForm $parentForm): RedirectResponse
    {
        $parentForm->update(['all_submissions_completed' => true]);
        session()->forget(['ropa_form_id', 'ropa_submission_id']);

        return redirect()->route('ropa.index')
            ->with('success', 'All sub-processes submitted successfully! Your RoPA form is now complete.');
    }

    public function addSubProcess(RopaForm $parentForm): RedirectResponse
    {
        abort_unless($this->canAccess($parentForm), 403);

        return $this->handleAddAnotherSubProcess($parentForm);
    }

    public function finalize(RopaForm $parentForm): RedirectResponse
    {
        abort_unless($this->canAccess($parentForm), 403);

        return $this->handleFinalize($parentForm);
    }

    public function addMore(RopaForm $parentForm): View|RedirectResponse
    {
        abort_unless($this->canAccess($parentForm), 403);

        $parentForm->load('college');
        $completedSubmissions = $parentForm->submissions()->where('status', 'completed')->get();
        if ($completedSubmissions->isEmpty()) {
            return redirect()->route('ropa.edit', ['step' => 1]);
        }

        $currentSubmission = $completedSubmissions->last();

        return view('ropa.add-more', compact('parentForm', 'currentSubmission', 'completedSubmissions'));
    }

    public function viewSubmission(RopaSubmission $submission): View
    {
        $parentForm = $submission->load('ropaForm.college')->ropaForm;

        if (! $parentForm) {
            abort(404, 'This submission is no longer linked to a RoPA process and cannot be viewed.');
        }

        abort_unless($this->canAccess($parentForm), 403);

        return view('ropa.show', compact('submission', 'parentForm'));
    }

    public function updateProcessIdentity(Request $request, RopaSubmission $submission): RedirectResponse
    {
        $parentForm = $submission->load('ropaForm.college')->ropaForm;

        if (! $parentForm || ! $this->canAccess($parentForm)) {
            abort(403);
        }

        $validated = $request->validate([
            'main_process_name' => 'sometimes|required|string|max:255',
            'sub_process_name' => 'sometimes|nullable|string|max:255',
        ]);

        if (array_key_exists('main_process_name', $validated)) {
            $parentForm->update(['main_process_name' => $validated['main_process_name']]);
        }

        if (array_key_exists('sub_process_name', $validated)) {
            $submission->update(['sub_process_name' => $validated['sub_process_name']]);
        }

        return redirect()->route('ropa.view-submission', $submission)
            ->with('success', 'Process name updated successfully.');
    }

    public function updateSubmission(Request $request, RopaSubmission $submission): RedirectResponse
    {
        $parentForm = $submission->load('ropaForm.college')->ropaForm;

        if (! $parentForm || ! $this->canAccess($parentForm)) {
            abort(403);
        }

        $isAdmin = $this->isAdminUser();
        $rules = [];
        for ($step = 1; $step <= 14; $step++) {
            $rules = array_merge($rules, $this->getValidationRules($step));
        }
        unset($rules['current_step'], $rules['action']);

        $relevantRules = array_intersect_key($rules, $request->all());
        if (! $isAdmin) {
            foreach (array_keys($this->adminOnlySubmissionFields()) as $field) {
                unset($relevantRules[$field]);
            }
        }

        $validated = $request->validate($relevantRules);

        $personnelFields = ['personnel_id', 'firstname', 'surname', 'role_responsible'];
        $formUpdateData = array_intersect_key($validated, array_flip($personnelFields));
        $submissionUpdateData = array_diff_key($validated, array_flip($personnelFields));

        if (! empty($formUpdateData)) {
            $parentForm->update($formUpdateData);
        }

        if (! empty($submissionUpdateData)) {
            $submission->update($submissionUpdateData);
        }

        return redirect()->route('ropa.view-submission', $submission)
            ->with('success', 'Submission updated successfully.');
    }

    private function adminOnlySubmissionFields(): array
    {
        return array_fill_keys([
            'dpia_required', 'dpia_progress', 'dpia_link', 'dpia_screening_required', 'dpia_screening_notes',
            'breach_occurred', 'breach_link',
        ], true);
    }

    public function destroySubmission(Request $request, RopaSubmission $submission): RedirectResponse
    {
        $parentForm = $submission->ropaForm;

        if (! $parentForm || ! $this->canAccess($parentForm)) {
            abort(403);
        }

        $scope = $request->validate(['scope' => 'nullable|in:submission,form'])['scope'] ?? 'submission';

        if ($scope === 'form') {
            DB::transaction(function () use ($parentForm) {
                $parentForm->submissions()->delete();
                $parentForm->delete();
            });

            if (session('ropa_form_id') == $parentForm->id) {
                session()->forget(['ropa_form_id', 'ropa_submission_id']);
            }

            return redirect()->route('ropa.index')
                ->with('success', 'The RoPA process and all its sub-processes were deleted.');
        }

        $submission->delete();

        if (session('ropa_submission_id') == $submission->id) {
            session()->forget('ropa_submission_id');
        }

        return redirect()->route('ropa.index')
            ->with('success', 'The sub-process was deleted.');
    }

    public function destroy(RopaForm $ropaForm): RedirectResponse
    {
        abort_unless($this->canAccess($ropaForm), 403);

        DB::transaction(function () use ($ropaForm) {
            $ropaForm->submissions()->delete();
            $ropaForm->delete();
        });

        if ((int) session('ropa_form_id') === $ropaForm->id) {
            session()->forget(['ropa_form_id', 'ropa_submission_id']);
        }

        return redirect()->route('ropa.index')
            ->with('success', 'The RoPA process and all its sub-processes were deleted.');
    }

    private function handleNavigation(Request $request, RopaForm $parentForm, RopaSubmission $submission)
    {
        abort_unless($this->canAccess($parentForm), 403);
        abort_unless($submission->ropa_form_id === $parentForm->id, 403);

        $targetStep = (int) $request->input('target_step');
        $currentStep = (int) $request->input('current_step', 1);
        $isAdmin = $this->isAdminUser();

        if (! $isAdmin && in_array($targetStep, [12, 13], true)) {
            $targetStep = 14;
        }
        if (! $isAdmin && in_array($currentStep, [12, 13], true)) {
            abort(403);
        }

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
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            report($e);
            $message = 'Unable to navigate to the selected step. Please check your entries and try again.';

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }

            return back()->withErrors($message);
        }
    }

    private function normalizeRequest(Request $request): void
    {
        $booleans = [
            'share_internally', 'lia_documented', 'legally_required_retention', 'auto_decision_making',
            'dpia_required', 'breach_occurred', 'retained_per_policy', 'processor_contract_in_place',
            'international_transfer_yn', 'dpia_screening_required', 'consent_used',
        ];
        foreach ($booleans as $field) {
            if ($request->has($field)) {
                $request->merge([$field => $request->boolean($field)]);
            }
        }

        $jsonFields = [
            'process_names', 'joint_controllers', 'categories_records', 'data_subjects', 'personal_data_categories',
            'internal_recipients', 'special_category_recipients', 'legal_basis', 'sensitive_legal_basis',
            'individual_rights', 'external_recipients', 'international_transfers', 'transfer_mechanisms',
            'dpa_conditions', 'gdpr_articles', 'cybersecurity_articles', 'other_articles',
        ];
        foreach ($jsonFields as $field) {
            if ($request->has($field)) {
                $value = $request->input($field);
                if (is_string($value)) {
                    $trimmed = trim($value);
                    if (str_starts_with($trimmed, '[') || str_starts_with($trimmed, '{')) {
                        $decoded = json_decode($trimmed, true);
                        $request->merge([$field => json_last_error() === JSON_ERROR_NONE ? $decoded : ($value !== '' ? [$value] : [])]);
                    } else {
                        $request->merge([$field => $value !== '' ? [$value] : []]);
                    }
                }
            }
        }

        foreach (['categories_records', 'individual_rights', 'dpa_conditions', 'gdpr_articles', 'cybersecurity_articles', 'other_articles', 'legal_basis', 'sensitive_legal_basis'] as $field) {
            if (! $request->has($field) || $request->input($field) === null) {
                $request->merge([$field => []]);
            }
        }
    }

    private function safeUrlRule(bool $required = false): array
    {
        $rules = [$required ? 'required' : 'nullable', 'string', 'max:500'];

        $rules[] = function (string $attribute, mixed $value, \Closure $fail): void {
            if ($value === null || $value === '') {
                return;
            }

            $validated = filter_var($value, FILTER_VALIDATE_URL);
            $scheme = strtolower((string) parse_url((string) $value, PHP_URL_SCHEME));
            if ($validated === false || ! in_array($scheme, ['http', 'https'], true)) {
                $fail('The '.$attribute.' must be a valid http or https URL.');
            }
        };

        return $rules;
    }

    private function getValidationRules(int $step): array
    {
        $baseRules = [
            'current_step' => 'nullable|integer|min:1|max:14',
            'action' => 'nullable|string|in:next,previous,save,submit,navigate',
        ];

        $stepRules = [
            1 => ['purpose' => 'nullable|string'],
            2 => ['joint_controllers' => 'required|array|min:1'],
            3 => [
                'categories_records' => 'required|array|min:1',
                'data_subjects' => 'nullable|array',
                'personal_data_categories' => 'nullable|array',
                'special_category_documents' => 'nullable|string',
                'internal_sharing_categories' => 'nullable|string',
                'detailed_processing_description' => 'nullable|string',
                'data_quality_controls' => 'nullable|string',
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
                'collection_method' => 'nullable|string',
                'systems_applications' => 'nullable|string',
                'data_flow_movement' => 'nullable|string',
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
                'lia_link' => $this->safeUrlRule(),
                'individual_rights' => 'nullable|array',
                'retention_trigger' => 'nullable|string|max:255',
                'retention_authority_evidence' => 'nullable|string',
                'disposal_method' => 'nullable|string|max:255',
                'rights_request_process' => 'nullable|string',
            ],
            7 => [
                'security_measures' => 'nullable|string',
                'access_control_user_roles' => 'nullable|string',
            ],
            8 => [
                'external_recipients' => 'required|array|min:1',
                'processing_role' => 'nullable|string|in:Data Controller,Data Processor,Joint Controller',
                'processor_contract_in_place' => 'nullable|boolean',
            ],
            9 => [
                'international_transfers' => 'nullable|array',
                'transfer_mechanisms' => 'nullable|array',
                'international_transfer_yn' => 'nullable|boolean',
                'transfer_destination_org' => 'nullable|string|max:255',
                'transfer_safeguards' => 'nullable|string',
            ],
            10 => [
                'auto_decision_making' => 'nullable|boolean',
                'profiling_description' => 'nullable|string|required_if:auto_decision_making,true',
            ],
            11 => [
                'consent_link' => $this->safeUrlRule(),
                'data_location' => 'nullable|string',
            ],
            12 => [
                'dpia_required' => 'nullable|boolean',
                'dpia_progress' => 'nullable|string|in:not_started,in_progress,completed',
                'dpia_link' => $this->safeUrlRule(),
                'dpia_screening_required' => 'nullable|boolean',
                'dpia_screening_notes' => 'nullable|string',
            ],
            13 => [
                'breach_occurred' => 'nullable|boolean',
                'breach_link' => array_merge($this->safeUrlRule(), ['required_if:breach_occurred,true']),
            ],
            14 => [
                'dpa_conditions' => 'required|array|min:1',
                'gdpr_articles' => 'required|array|min:1',
                'cybersecurity_articles' => 'nullable|array',
                'other_articles' => 'nullable|array',
                'retention_policy_link' => $this->safeUrlRule(true),
                'retained_per_policy' => 'required|boolean',
                'retention_non_adherence_reason' => 'nullable|string|required_if:retained_per_policy,false',
            ],
        ];

        return array_merge($baseRules, $stepRules[$step] ?? []);
    }

    public function signOffProcessingOwner(RopaForm $parentForm): RedirectResponse
    {
        abort_unless($this->canAccess($parentForm), 403);
        $parentForm->update(['processing_owner_signed_at' => now()]);

        return back()->with('success', 'Processing Owner sign-off recorded.');
    }

    public function validatePrivacyOffice(RopaForm $parentForm): RedirectResponse
    {
        abort_unless($this->isAdminUser(), 403);
        $parentForm->update(['privacy_office_validated_at' => now()]);

        return back()->with('success', 'Privacy Office validation recorded.');
    }

    public function approveForm(RopaForm $parentForm): RedirectResponse
    {
        abort_unless($this->isAdminUser(), 403);
        $parentForm->update(['approved_at' => now()]);

        return back()->with('success', 'Form approved.');
    }
}
