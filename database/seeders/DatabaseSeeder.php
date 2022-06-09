<?php

namespace Database\Seeders;

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

        //user table
        User::create([
            'name' => 'Admin',
            'email' =>'admin@gmail.com',
            'password'=>Hash::make(12345678910),
            'department' => 'boat',
            'role'=>"ADMIN"
        ]);
        User::create([
            'name' => 'teacher',
            'email' => 'teacher@gmail.com',
            'password' => Hash::make(12345678910),
            'department' => 'CSE',
            'role' => "TEACHER"
        ]);
        User::create([
            'name' => 'dean',
            'email' => 'dean@gmail.com',
            'password' => Hash::make(12345678910),
            'department' => 'CSE',
            'role' => "DEAN"
        ]);
        //session table
        SessionYear::create([
            'session_year'=>'2020-2021',
            'session_type'=>'SUMMER'
        ]);
    }
}
