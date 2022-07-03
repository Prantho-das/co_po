<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\ProgramOutcome;
use App\Models\Semester;
use App\Models\SessionYear;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        //session table
        Department::create([
            'name' => 'boat',
        ]);
        // user table
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make(12345678910),
            'department' => 'boat',
            'role' => "ADMIN",
            'department_id' => 1,
        ]);
        User::create([
            'name' => 'teacher',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make(12345678910),
            'department' => 'CSE',
            'role' => "TEACHER",
            'department_id' => 1,
        ]);
        User::create([
            'name' => 'dean',
            'email' => 'dean@gmail.com',
            'password' => Hash::make(12345678910),
            'department' => 'CSE',
            'role' => "DEAN",
            'department_id' => 1,
        ]);
        //session table
        SessionYear::create([
            'session_year' => '2020-2021',
            'session_type' => 'SUMMER'
        ]);
        ProgramOutcome::insert([
            [
                'po_name' => 'Engineering Knowledge',
                'po_no' => 'PO-A'
            ],
            [
                'po_name' => 'Problem Analysis',
                'po_no' => 'PO-B'
            ],
            [
                'po_name' => 'Design/Development of Solution',
                'po_no' => 'PO-C'
            ],
            [
                'po_name' => 'Investigation',
                'po_no' => 'PO-D'
            ],
            [
                'po_name' => 'Modern Tool Usage',
                'po_no' => 'PO-E'
            ],
            [
                'po_name' => 'The Engineer and Society',
                'po_no' => 'PO-F'
            ],
            [
                'po_name' => 'Environment and Sustainability',
                'po_no' => 'PO-G'
            ],
            [
                'po_name' => 'Ethics',
                'po_no' => 'PO-H'
            ],
            [
                'po_name' => 'Individual Work and Teamwork',
                'po_no' => 'PO-I'
            ],
            [
                'po_name' => 'Communication',
                'po_no' => 'PO-J'
            ],
            [
                'po_name' => 'Project Management and Finance',
                'po_no' => 'PO-K'
            ],
             [
                'po_name' => 'Life-long Learning',
                'po_no' => 'PO-L'
            ]
        ]);
           for ($i=1; $i <= 12; $i++) {
            Semester::create([
                'name'=>$i%2==0?$i."nd":$i."st",
            ]);
           }
        Course::create([
            'c_name' => 'Artificial Intelligence and  Neural Networks',
            'c_code' => 'CSE-407',
            'credit' => 3,
            'semester_id' => 11,
            'semester_name' => '11th'
        ]);
    }
}
