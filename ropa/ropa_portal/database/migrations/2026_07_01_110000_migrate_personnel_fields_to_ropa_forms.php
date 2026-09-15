<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $forms = DB::table('ropa_forms')->get();

            foreach ($forms as $form) {
                $firstSubmission = DB::table('ropa_submissions')
                    ->where('ropa_form_id', $form->id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($firstSubmission) {
                    DB::table('ropa_forms')
                        ->where('id', $form->id)
                        ->update([
                            'firstname' => $firstSubmission->firstname,
                            'surname' => $firstSubmission->surname,
                            'personnel_id' => $firstSubmission->personnel_id,
                            'role_responsible' => $firstSubmission->role_responsible,
                        ]);
                }
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            $forms = DB::table('ropa_forms')->get();

            foreach ($forms as $form) {
                $firstSubmission = DB::table('ropa_submissions')
                    ->where('ropa_form_id', $form->id)
                    ->orderBy('id', 'asc')
                    ->first();

                if ($firstSubmission) {
                    DB::table('ropa_submissions')
                        ->where('id', $firstSubmission->id)
                        ->update([
                            'firstname' => $form->firstname,
                            'surname' => $form->surname,
                            'personnel_id' => $form->personnel_id,
                            'role_responsible' => $form->role_responsible,
                        ]);
                }
            }
        });
    }
};
