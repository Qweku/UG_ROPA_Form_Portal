<?php

namespace Database\Seeders;

use App\Models\College;
use App\Models\School;
use Illuminate\Database\Seeder;

class CollegeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // Database/Seeders/CollegeSeeder.php
    public function run()
    {
        $colleges = [
            'College of Humanities' => ['School of Arts', 'School of Social Sciences', 'School of Law'],
            'College of Education' => ['School of Educational Development and Outreach', 'School of Continuing and Distance Education'],
            'College of Basic and Applied Sciences' => ['School of Physical and Mathematical Sciences', 'School of Biological Sciences', 'School of Engineering'],
            'College of Health Sciences' => ['School of Medicine and Dentistry', 'School of Pharmacy', 'School of Public Health', 'School of Nursing and Midwifery'],
        ];

        foreach ($colleges as $collegeName => $schools) {
            $college = College::create(['name' => $collegeName]);
            foreach ($schools as $school) {
                School::create(['college_id' => $college->id, 'name' => $school]);
            }
        }
    }
}
