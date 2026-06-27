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
     * True if the current user owns the given RopaForm, or is an admin.
     * Centralizes the "owner or admin" check used across every method
     * that guards access to a specific RopaForm/RopaSubmission.
     */
    private function canAccess(RopaForm $parentForm): bool
    {
        $user = Auth::user();

        return $user && ($parentForm->user_id === $user->id || $user->role === 'admin');
    }

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
     * Entry point for "Create New RoPA Process".
     *
     * A user may only have ONE incomplete RoPA form at a time. If they
     * already have one (whether or not it's pointed to by the current
     * session — e.g. a previous tab, an expired session, or a stale
     * "Create" click), we resume that one instead of creating a new row.
     * A brand new RopaForm is only ever created from here when the user
     * genuinely has none in progress.
     */
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

    /**
     * Point the session at an existing incomplete form and land the user
     * on the right step (its current draft submission, or "add more" if
     * the most recent submission is already completed). Flashes a message
     * so the user understands they were routed back into existing work
     * rather than given a fresh blank form.
     */
    private function resumeForm(RopaForm $parentForm): RedirectResponse
    {
        $draftSubmission = $parentForm->submissions()
            ->where('status', 'draft')
            ->latest('updated_at')
            ->first();

        if (! $draftSubmission) {
            // No open draft — most recent submission was completed.
            // Let the add-more screen decide what happens next; do not
            // create a new submission row here.
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

    /**
     * Edit a specific step of the current submission.
     *
     * This is a GET endpoint and must be side-effect-free with respect to
     * row creation: it should only ever look up existing records. If the
     * session pointers are missing or stale (new tab, expired session,
     * direct navigation), it re-attaches to the user's existing incomplete
     * form rather than silently inserting a new RopaForm/RopaSubmission.
     * A new pair is only created as a last resort, when the user truly
     * has no incomplete form at all (e.g. they navigated here directly
     * without ever going through "Create").
     */
    public function edit($step = 1): View|RedirectResponse
    {
        $user = Auth::user();

        $parentForm = session('ropa_form_id') ? RopaForm::find(session('ropa_form_id')) : null;

        // Session pointer missing/stale — try to recover the user's
        // existing incomplete form before considering creating a new one.
        if (! $parentForm || $parentForm->user_id !== $user->id) {
            $parentForm = RopaForm::where('user_id', $user->id)
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

        // Session pointer missing/stale, or pointing at a submission that
        // doesn't belong to the resolved parent form — recover the
        // parent's current draft instead of creating a new one.
        if (! $submission || $submission->ropaForm->id !== $parentForm->id) {
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

        $colleges = College::all();

        if ($step >= 2 && $step <= 14) {
            return view('ropa.form', compact('parentForm', 'submission', 'step', 'colleges'));
        }

        return view('ropa.steps.step1', compact('parentForm', 'submission', 'colleges'));
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
        if (! $this->canAccess($parentForm)) {
            abort(403);
        }

        return $this->handleAddAnotherSubProcess($parentForm);
    }

    public function finalize(RopaForm $parentForm): RedirectResponse
    {
        if (! $this->canAccess($parentForm)) {
            abort(403);
        }

        return $this->handleFinalize($parentForm);
    }

    /**
     * Show the "add more" view after a sub-process is completed.
     */
    public function addMore(RopaForm $parentForm): View|RedirectResponse
    {
        if (! $this->canAccess($parentForm)) {
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
     * View a single submission (full details, with inline edit + delete).
     */
    public function viewSubmission(RopaSubmission $submission): View
    {
        $parentForm = $submission->ropaForm;

        if (! $parentForm) {
            abort(404, 'This submission is no longer linked to a RoPA process and cannot be viewed.');
        }

        if (! $this->canAccess($parentForm)) {
            abort(403);
        }

        return view('ropa.show', compact('submission', 'parentForm'));
    }

    /**
     * Inline update of a single field (or set of fields) on an already
     * completed submission, from the show page. This is intentionally
     * separate from update() above, which drives the step-by-step wizard
     * via session state — this one operates directly on a known
     * RopaSubmission with no session/step bookkeeping involved.
     */
    /**
     * Update the process identity fields shown at the top of the show page:
     * - main_process_name lives on the parent RopaForm and is shared across
     *   every sub-process under it, so saving this updates all siblings too.
     * - sub_process_name lives on this RopaSubmission only.
     * Both are optional in the request so the two fields can be edited
     * (and saved) independently from one shared card.
     */
    public function updateProcessIdentity(Request $request, RopaSubmission $submission): RedirectResponse
    {
        $parentForm = $submission->ropaForm;

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
        $parentForm = $submission->ropaForm;

        if (! $parentForm || ! $this->canAccess($parentForm)) {
            abort(403);
        }

        // Merge the validation rules for every step into one rule set,
        // since inline editing can touch fields from any section.
        $rules = [];
        for ($step = 1; $step <= 14; $step++) {
            $rules = array_merge($rules, $this->getValidationRules($step));
        }
        // current_step/action aren't real submission fields here; drop them.
        unset($rules['current_step'], $rules['action']);

        // Only validate the fields actually present in this request, so a
        // single-section save doesn't fail on required fields from other
        // sections it didn't touch.
        $relevantRules = array_intersect_key($rules, $request->all());

        $validated = $request->validate($relevantRules);

        $submission->update($validated);

        return redirect()->route('ropa.view-submission', $submission)
            ->with('success', 'Submission updated successfully.');
    }

    /**
     * Delete either a single sub-process (RopaSubmission) or the entire
     * parent RopaForm and all of its submissions, depending on what the
     * user confirmed in the delete modal.
     */
    public function destroySubmission(Request $request, RopaSubmission $submission): RedirectResponse
    {
        $parentForm = $submission->ropaForm;

        if (! $parentForm || ! $this->canAccess($parentForm)) {
            abort(403);
        }

        $scope = $request->input('scope', 'submission');

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
