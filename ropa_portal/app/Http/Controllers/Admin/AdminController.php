<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RopaForm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
  

    /**
     * Admin Dashboard
     */
    public function dashboard()
    {
        $stats = [
            'total_forms' => RopaForm::count(),
            'submitted_forms' => RopaForm::where('status', 'submitted')->count(),
            'approved_forms' => RopaForm::where('status', 'approved')->count(),
            'rejected_forms' => RopaForm::where('status', 'rejected')->count(),
            'draft_forms' => RopaForm::where('status', 'draft')->count(),
            'total_users' => User::where('role', '!=', 'admin')->count(),
            'active_users' => User::where('role', '!=', 'admin')->where('is_verified', true)->count(),
            'forms_this_month' => RopaForm::whereMonth('created_at', now()->month)->count(),
        ];

        $recentForms = RopaForm::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentUsers = User::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentForms', 'recentUsers'));
    }

    /**
     * List all submitted forms
     */
    public function submittedForms(Request $request)
    {
        $query = RopaForm::with('user')
            ->whereIn('status', ['submitted', 'approved', 'rejected']);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'LIKE', "%{$search}%")
                  ->orWhere('personnel_id', 'LIKE', "%{$search}%")
                  ->orWhere('surname', 'LIKE', "%{$search}%")
                  ->orWhere('firstname', 'LIKE', "%{$search}%")
                  ->orWhere('business_function', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q2) use ($search) {
                      $q2->where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date filter
        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        $forms = $query->orderBy('submitted_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'submitted' => RopaForm::where('status', 'submitted')->count(),
            'approved' => RopaForm::where('status', 'approved')->count(),
            'rejected' => RopaForm::where('status', 'rejected')->count(),
        ];

        return view('admin.submitted-forms', compact('forms', 'stats'));
    }

    /**
     * View single form details
     */
    public function viewForm(RopaForm $ropaForm)
    {
        if (!in_array($ropaForm->status, ['submitted', 'approved', 'rejected'])) {
            return redirect()->route('admin.submitted-forms')
                ->with('warning', 'This form is not in submitted status.');
        }

        return view('admin.view-form', compact('ropaForm'));
    }

    /**
     * Approve form
     */
    public function approveForm(RopaForm $ropaForm, Request $request)
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($ropaForm, $request) {
            $ropaForm->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
                'approval_notes' => $request->approval_notes,
            ]);
        });

        return redirect()->route('admin.submitted-forms')
            ->with('success', "Form #{$ropaForm->id} has been approved successfully.");
    }

    /**
     * Reject form with reason
     */
    public function rejectForm(RopaForm $ropaForm, Request $request)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        DB::transaction(function () use ($ropaForm, $request) {
            $ropaForm->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $request->rejection_reason,
            ]);
        });

        return redirect()->route('admin.submitted-forms')
            ->with('success', "Form #{$ropaForm->id} has been rejected. Reason has been recorded.");
    }

    /**
     * Export forms to CSV
     */
    public function exportForms(Request $request)
    {
        $query = RopaForm::with('user')
            ->whereIn('status', ['submitted', 'approved', 'rejected']);

        // Apply same filters as listing
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('submitted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('submitted_at', '<=', $request->date_to);
        }

        $forms = $query->orderBy('submitted_at', 'desc')->get();

        $filename = 'ropa_forms_export_' . date('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($forms) {
            $file = fopen('php://output', 'w');

            // Add UTF-8 BOM for Excel compatibility
            fwrite($file, "\xEF\xBB\xBF");

            // Headers
            fputcsv($file, [
                'Form ID', 'Submitter Name', 'Email', 'Personnel ID', 'Business Function',
                'Process Names', 'Status', 'Submitted At', 'Approved/Rejected At',
                'Data Subjects', 'Personal Data Categories', 'Legal Basis', 'Retention Period'
            ]);

            // Data rows
            foreach ($forms as $form) {
                fputcsv($file, [
                    $form->id,
                    $form->user->name ?? 'N/A',
                    $form->user->email ?? 'N/A',
                    $form->personnel_id ?? 'N/A',
                    $form->business_function ?? 'N/A',
                    implode(', ', json_decode($form->process_names ?? '[]', true) ?: []),
                    $form->status,
                    $form->submitted_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $form->approved_at?->format('Y-m-d H:i:s') ?? $form->rejected_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    implode(', ', json_decode($form->data_subjects ?? '[]', true) ?: []),
                    implode(', ', json_decode($form->personal_data_categories ?? '[]', true) ?: []),
                    implode(', ', json_decode($form->legal_basis ?? '[]', true) ?: []),
                    $form->retention_period ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk action on forms
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'form_ids' => 'required|array',
            'form_ids.*' => 'exists:ropa_forms,id',
            'action' => 'required|in:approve,reject,export',
        ]);

        $forms = RopaForm::whereIn('id', $request->form_ids)->get();

        if ($request->action === 'approve') {
            foreach ($forms as $form) {
                if ($form->status === 'submitted') {
                    $form->update([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'approved_by' => auth()->id(),
                    ]);
                }
            }
            $message = count($forms) . ' form(s) approved successfully.';
        } elseif ($request->action === 'reject') {
            $request->validate([
                'rejection_reason' => 'required_if:action,reject|string|max:500',
            ]);

            foreach ($forms as $form) {
                if ($form->status === 'submitted') {
                    $form->update([
                        'status' => 'rejected',
                        'rejected_at' => now(),
                        'rejected_by' => auth()->id(),
                        'rejection_reason' => $request->rejection_reason,
                    ]);
                }
            }
            $message = count($forms) . ' form(s) rejected successfully.';
        } else {
            return $this->exportForms($request);
        }

        return redirect()->route('admin.submitted-forms')
            ->with('success', $message);
    }
}
