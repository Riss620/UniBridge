<?php

namespace Database\Seeders;

use App\Models\GovernmentUser;
use App\Models\Student;
use App\Models\University;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'              => 'Super Admin',
            'email'             => 'admin@unibridge.in',
            'password'          => Hash::make('Admin@1234'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // Government User
        $govtUser = User::create([
            'name'              => 'Rahul Sharma',
            'email'             => 'govt@unibridge.in',
            'password'          => Hash::make('Govt@1234'),
            'role'              => 'government',
            'email_verified_at' => now(),
        ]);
        GovernmentUser::create([
            'user_id'     => $govtUser->id,
            'department'  => 'Ministry of Education',
            'designation' => 'Director',
            'ministry'    => 'Ministry of Education, Government of India',
        ]);

        // University User
        $univUser = User::create([
            'name'              => 'Delhi University Admin',
            'email'             => 'du@unibridge.in',
            'password'          => Hash::make('Univ@1234'),
            'role'              => 'university',
            'email_verified_at' => now(),
        ]);
        $university = University::create([
            'user_id'        => $univUser->id,
            'name'           => 'University of Delhi',
            'address'        => 'University Road, Delhi 110007',
            'city'           => 'New Delhi',
            'state'          => 'Delhi',
            'affiliation_no' => 'DU-CENT-001',
            'contact_phone'  => '011-27667011',
            'type'           => 'central',
            'status'         => 'approved',
        ]);

        // Sample students
        $courses = ['B.Tech', 'M.Tech', 'B.Sc', 'M.Sc', 'MBA', 'BCA', 'MCA'];
        $depts   = ['Computer Science', 'Electronics', 'Physics', 'Chemistry', 'Management', 'Mathematics'];
        $states  = ['Delhi', 'UP', 'Bihar', 'Rajasthan', 'Punjab', 'Haryana'];
        for ($i = 1; $i <= 20; $i++) {
            Student::create([
                'university_id'  => $university->id,
                'name'           => "Student {$i}",
                'roll_no'        => "DU2024" . str_pad($i, 4, '0', STR_PAD_LEFT),
                'email'          => "student{$i}@du.ac.in",
                'course'         => $courses[array_rand($courses)],
                'department'     => $depts[array_rand($depts)],
                'year'           => rand(1, 4),
                'cgpa'           => round(rand(60, 100) / 10, 1),
                'status'         => 'active',
                'admission_year' => 2022,
                'gender'         => rand(0, 1) ? 'male' : 'female',
                'state_of_origin'=> $states[array_rand($states)],
            ]);
        }

        // Student user
        User::create([
            'name'              => 'Priya Patel',
            'email'             => 'student1@du.ac.in',
            'password'          => Hash::make('Student@1234'),
            'role'              => 'student',
            'email_verified_at' => now(),
        ]);
    }
}
