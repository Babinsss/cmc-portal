<?php

namespace Database\Seeders;

use App\Models\JobPosting;
use Illuminate\Database\Seeder;

class JobPostingSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = [
            [
                'title' => 'Medical Officer III (Internal Medicine Resident)',
                'department' => 'Department of Internal Medicine',
                'employment_type' => 'Residency Training',
                'salary_grade' => 'SG-21',
                'vacancies_count' => 4,
                'deadline' => now()->addDays(45)->toDateString(),
                'description' => 'Join our PCP-Accredited Internal Medicine Residency Training Program providing 24/7 compassionate care across medical wards, ICU, and outpatient clinics.',
                'qualifications' => "• Doctor of Medicine (MD) with valid PRC Physician License\n• Completed 1-year Post-Graduate Internship\n• Updated PRC Board Rating and Transcript of Records\n• Good moral character and willingness to serve on 24/7 clinical duty rotations",
                'is_open' => true,
            ],
            [
                'title' => 'Nurse II (Emergency & Ward Operations)',
                'department' => 'Nursing Service Division',
                'employment_type' => 'Permanent',
                'salary_grade' => 'SG-16',
                'vacancies_count' => 6,
                'deadline' => now()->addDays(30)->toDateString(),
                'description' => 'Provide bedside nursing care, patient triage, medication administration, and family health education in a Level II public hospital setting.',
                'qualifications' => "• Bachelor of Science in Nursing with valid PRC RN License\n• At least 1 year of relevant hospital clinical experience\n• Current BLS / ACLS certification is an advantage",
                'is_open' => true,
            ],
            [
                'title' => 'Medical Technologist I',
                'department' => 'Tertiary Level Clinical Laboratory',
                'employment_type' => 'Permanent',
                'salary_grade' => 'SG-11',
                'vacancies_count' => 2,
                'deadline' => now()->addDays(30)->toDateString(),
                'description' => 'Perform laboratory assays across Blood Chemistry, Hematology, Serology, Microscopy, and Blood Station shifts.',
                'qualifications' => "• BS in Medical Technology with valid PRC RMT License\n• Willing to work on shifting 24/7 hospital laboratory schedules",
                'is_open' => true,
            ],
        ];

        foreach ($jobs as $job) {
            JobPosting::query()->updateOrCreate(
                ['title' => $job['title'], 'department' => $job['department']],
                $job,
            );
        }
    }
}
