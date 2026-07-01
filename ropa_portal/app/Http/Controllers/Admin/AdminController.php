<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Auth\OtpVerificationController;
use App\Http\Controllers\Controller;
use App\Models\RopaForm;
use App\Models\RopaSubmission;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_forms' => RopaForm::count(),
            'completed_forms' => RopaForm::where('all_submissions_completed', true)->count(),
            'in_progress_forms' => RopaForm::where('all_submissions_completed', false)->count(),
            'total_submissions' => RopaSubmission::count(),
            'completed_submissions' => RopaSubmission::where('status', 'completed')->count(),
            'draft_submissions' => RopaSubmission::where('status', 'draft')->count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'active_users' => User::where('role', '!=', 'admin')->where('is_verified', true)->count(),
            'forms_this_month' => RopaForm::whereMonth('created_at', now()->month)->count(),
        ];

        $recentForms = RopaForm::with(['user', 'college', 'submissions'])
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        $recentUsers = User::where('role', '!=', 'admin')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentForms', 'recentUsers'));
    }

    /**
     * List all RoPA forms, with search/filter by completion state.
     * "Submitted forms" in the old workflow is now simply every RopaForm
     * that has at least started (i.e. all of them) — filterable by
     * whether all its sub-processes are completed yet.
     */
    public function submittedForms(Request $request)
    {
        $query = RopaForm::with(['user', 'college', 'submissions']);

        // Search filter — search the form, its owner, and its personnel fields.
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('main_process_name', 'LIKE', "%{$search}%")
                    ->orWhere('business_function', 'LIKE', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('firstname', 'LIKE', "%{$search}%")
                            ->orWhere('surname', 'LIKE', "%{$search}%")
                            ->orWhere('email', 'LIKE', "%{$search}%");
                    })
                    ->orWhere(function ($q3) use ($search) {
                        $q3->where('personnel_id', 'LIKE', "%{$search}%")
                            ->orWhere('firstname', 'LIKE', "%{$search}%")
                            ->orWhere('surname', 'LIKE', "%{$search}%");
                    });
            });
        }

        // Completion-state filter, replacing the old submitted/approved/
        // rejected status filter.
        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'completed') {
                $query->where('all_submissions_completed', true);
            } elseif ($request->status === 'in_progress') {
                $query->where('all_submissions_completed', false);
            }
        }

        // Date filter — filter by when the form was created, since there's
        // no single "submitted_at" moment in a multi-submission form.
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $forms = $query->orderBy('updated_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => RopaForm::count(),
            'completed' => RopaForm::where('all_submissions_completed', true)->count(),
            'in_progress' => RopaForm::where('all_submissions_completed', false)->count(),
        ];

        return view('admin.submitted-forms', compact('forms', 'stats'));
    }

    /**
     * View a single RoPA form's full detail — the parent form plus an
     * accordion of every sub-process submission under it. Admins can view
     * any form regardless of completion state (there's no "not yet
     * submitted" gate anymore, since every RopaForm is visible as soon as
     * it has at least one submission).
     */
    public function viewForm(RopaForm $ropaForm)
    {
        $ropaForm->load(['user', 'college', 'submissions']);

        return view('admin.view-form', compact('ropaForm'));
    }

    /**
     * Export forms to CSV. Exports one row per RopaSubmission (since that's
     * where the actual processing-activity data lives now), with the
     * parent form's identifying info repeated on each row.
     */
    public function exportForms(Request $request)
    {
        $query = RopaForm::with(['user', 'college', 'submissions']);

        if ($request->filled('status') && $request->status !== 'all') {
            if ($request->status === 'completed') {
                $query->where('all_submissions_completed', true);
            } elseif ($request->status === 'in_progress') {
                $query->where('all_submissions_completed', false);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $forms = $query->orderBy('updated_at', 'desc')->get();

        $filename = 'ropa_forms_export_'.date('Y-m-d_His').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($forms) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Form ID', 'Submitter Name', 'Email', 'College', 'Business Function',
                'Main Process Name', 'Sub-process Name', 'Submission Status',
                'Personnel ID', 'Personnel Name', 'Data Subjects',
                'Personal Data Categories', 'Legal Basis', 'Retention Period',
                'Completed At',
            ]);

            // One row per submission, so multi-sub-process forms produce
            // multiple rows sharing the same Form ID.
            foreach ($forms as $form) {
                if ($form->submissions->isEmpty()) {
                    fputcsv($file, [
                        $form->id,
                        $form->user->name ?? trim(($form->user->firstname ?? '').' '.($form->user->surname ?? '')) ?: 'N/A',
                        $form->user->email ?? 'N/A',
                        $form->college->name ?? 'N/A',
                        $form->business_function ?? 'N/A',
                        $form->main_process_name ?? 'N/A',
                        'N/A', 'No submissions yet', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A',
                    ]);

                    continue;
                }

                foreach ($form->submissions as $submission) {
                    fputcsv($file, [
                        $form->id,
                        $form->user->name ?? trim(($form->user->firstname ?? '').' '.($form->user->surname ?? '')) ?: 'N/A',
                        $form->user->email ?? 'N/A',
                        $form->college->name ?? 'N/A',
                        $form->business_function ?? 'N/A',
                        $form->main_process_name ?? 'N/A',
                        $submission->sub_process_name ?? 'N/A (main process)',
                        ucfirst($submission->status),
                        $form->personnel_id ?? 'N/A',
                        trim(($form->firstname ?? '').' '.($form->surname ?? '')) ?: 'N/A',
                        implode(', ', $submission->data_subjects ?? []),
                        implode(', ', $submission->personal_data_categories ?? []),
                        implode(', ', $submission->legal_basis ?? []),
                        $submission->retention_period ?? 'N/A',
                        $submission->completed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ================================================================
    // User Management
    // ================================================================

    /**
     * List all users (excluding other admins from accidental self-lockout
     * scenarios is NOT enforced here — admins can see and manage other
     * admins too — but the currently logged-in admin is flagged in the
     * view so the UI can disable self-delete).
     */
    public function users(Request $request): View
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('personnel_id', 'LIKE', "%{$search}%");
            });
        }

        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        $users = $query->withCount('ropaForms')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', compact('users'));
    }

    /**
     * Show the form to add a new user.
     */
    public function createUser(): View
    {
        return view('admin.user-form', ['user' => null]);
    }

    /**
     * Create a new user. Mirrors the public registration flow (same
     * validation, same OTP verification step) but does NOT log the admin
     * out of their own session — the new user must verify their own email
     * via OTP before they can log in themselves.
     */
    public function storeUser(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'personnel_id' => 'nullable|string|max:50',
            'role' => 'required|in:user,admin',
        ]);

        $user = User::create([
            'firstname' => $validated['firstname'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'personnel_id' => $validated['personnel_id'] ?? null,
            'role' => $validated['role'],
            'is_verified' => false,
        ]);

        // Send the same OTP verification email a self-registered user gets.
        // The admin's own session is untouched — we never call Auth::login()
        // here, unlike the public registration flow.
        (new OtpVerificationController)->sendOtp($user);

        return redirect()->route('admin.users')
            ->with('success', "User {$user->firstname} {$user->surname} was created. They'll need to verify their email via the OTP sent to {$user->email} before they can log in.");
    }

    /**
     * Show the form to edit an existing user.
     */
    public function editUser(User $user): View
    {
        return view('admin.user-form', compact('user'));
    }

    /**
     * Update a user's profile fields. Password is intentionally NOT
     * editable here — that's handled separately by resetPassword(), so a
     * profile edit can never accidentally wipe/change someone's password.
     */
    public function updateUser(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'personnel_id' => 'nullable|string|max:50',
            'role' => 'required|in:user,admin',
            'is_verified' => 'sometimes|boolean',
        ]);

        $validated['is_verified'] = $request->boolean('is_verified');

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', "User {$user->firstname} {$user->surname} was updated successfully.");
    }

    /**
     * Admin sets a new password directly for a user (no email/reset-link
     * round-trip). The 'hashed' cast on User::$casts['password'] hashes
     * this automatically on save.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => $validated['password']]);

        return redirect()->route('admin.users')
            ->with('success', "Password for {$user->firstname} {$user->surname} has been reset.");
    }

    /**
     * Permanently delete a user and ALL of their RoPA data (every RopaForm
     * they own and every RopaSubmission under those forms). This is
     * irreversible — the confirmation step lives in the view, not here.
     */
    public function destroyUser(Request $request, User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users')
                ->with('error', 'You cannot delete your own account while logged in as it.');
        }

        DB::transaction(function () use ($user) {
            $formIds = $user->ropaForms()->pluck('id');

            RopaSubmission::whereIn('ropa_form_id', $formIds)->delete();
            RopaForm::whereIn('id', $formIds)->delete();

            $user->delete();
        });

        return redirect()->route('admin.users')
            ->with('success', 'User and all their RoPA data have been permanently deleted.');
    }
}
