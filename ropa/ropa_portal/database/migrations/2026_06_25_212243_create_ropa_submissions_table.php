<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ropa_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ropa_form_id')->constrained()->onDelete('cascade');
            $table->string('sub_process_name')->nullable();
            $table->string('personnel_id')->nullable();
            $table->string('surname')->nullable();
            $table->string('firstname')->nullable();
            $table->text('purpose')->nullable();
            $table->string('role_responsible')->nullable();
            $table->json('joint_controllers')->nullable();
            $table->json('categories_records')->nullable();
            $table->json('data_subjects')->nullable();
            $table->json('personal_data_categories')->nullable();
            $table->text('special_category_documents')->nullable();
            $table->text('internal_sharing_categories')->nullable();
            $table->boolean('share_internally')->nullable();
            $table->json('internal_recipients')->nullable();
            $table->json('special_category_recipients')->nullable();
            $table->text('sharing_reasons')->nullable();
            $table->string('data_source')->nullable();
            $table->text('data_update_method')->nullable();
            $table->json('legal_basis')->nullable();
            $table->boolean('lia_documented')->nullable();
            $table->string('lia_location')->nullable();
            $table->json('sensitive_legal_basis')->nullable();
            $table->string('retention_period')->nullable();
            $table->boolean('legally_required_retention')->nullable();
            $table->text('special_category_condition')->nullable();
            $table->text('legitimate_interests')->nullable();
            $table->string('lia_link')->nullable();
            $table->json('individual_rights')->nullable();
            $table->text('security_measures')->nullable();
            $table->json('external_recipients')->nullable();
            $table->json('international_transfers')->nullable();
            $table->json('transfer_mechanisms')->nullable();
            $table->boolean('auto_decision_making')->nullable();
            $table->text('profiling_description')->nullable();
            $table->string('consent_link')->nullable();
            $table->text('data_location')->nullable();
            $table->boolean('dpia_required')->nullable();
            $table->string('dpia_progress')->nullable();
            $table->string('dpia_link')->nullable();
            $table->boolean('breach_occurred')->nullable();
            $table->string('breach_link')->nullable();
            $table->json('dpa_conditions')->nullable();
            $table->json('gdpr_articles')->nullable();
            $table->string('retention_policy_link')->nullable();
            $table->boolean('retained_per_policy')->nullable();
            $table->text('retention_non_adherence_reason')->nullable();

            $table->integer('current_step')->default(1);
            $table->enum('status', ['draft', 'completed'])->default('draft');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ropa_submissions');
    }
};
