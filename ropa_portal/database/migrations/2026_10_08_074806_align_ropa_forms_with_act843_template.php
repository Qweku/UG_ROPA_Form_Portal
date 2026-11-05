<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        /**
         * Fields added to the submission (ropa_submissions).
         * These are the operational / technical / data-specific fields
         * that can differ per sub-process.
         */
        Schema::table('ropa_submissions', function (Blueprint $table) {
            // Add role_responsible first (doesn't exist yet)
            if (!Schema::hasColumn('ropa_submissions', 'role_responsible')) {
                $table->string('role_responsible')->nullable()->after('access_control_user_roles');
            }

            // Activity-level controller/processor info
            if (!Schema::hasColumn('ropa_submissions', 'processing_role')) {
                $table->string('processing_role')->nullable()->after('role_responsible'); // Data Controller / Data Processor / Joint Controller
            }
            if (!Schema::hasColumn('ropa_submissions', 'processor_contract_in_place')) {
                $table->boolean('processor_contract_in_place')->nullable()->after('processing_role');
            }

            // Rights reference (in addition to rights list)
            if (!Schema::hasColumn('ropa_submissions', 'rights_request_process')) {
                $table->text('rights_request_process')->nullable()->after('individual_rights');
            }

            // International transfer Yes/No (we already have a list of countries)
            if (!Schema::hasColumn('ropa_submissions', 'international_transfer_yn')) {
                $table->boolean('international_transfer_yn')->default(false)->after('international_transfers');
            }
            if (!Schema::hasColumn('ropa_submissions', 'transfer_destination_org')) {
                $table->string('transfer_destination_org')->nullable()->after('international_transfer_yn');
            }
            if (!Schema::hasColumn('ropa_submissions', 'transfer_safeguards')) {
                $table->text('transfer_safeguards')->nullable()->after('transfer_destination_org');
            }

            // DPIA screening (vs. full DPIA)
            if (!Schema::hasColumn('ropa_submissions', 'dpia_screening_required')) {
                $table->boolean('dpia_screening_required')->default(false)->after('dpia_required');
            }
            if (!Schema::hasColumn('ropa_submissions', 'dpia_screening_notes')) {
                $table->text('dpia_screening_notes')->nullable()->after('dpia_screening_required');
            }
        });
    }

    public function down(): void
    {
        Schema::table('ropa_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'role_responsible',
                'processing_role', 'processor_contract_in_place',
                'rights_request_process',
                'international_transfer_yn', 'transfer_destination_org', 'transfer_safeguards',
                'dpia_screening_required', 'dpia_screening_notes',
            ]);
        });
    }
};