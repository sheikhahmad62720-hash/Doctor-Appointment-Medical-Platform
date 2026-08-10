<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\User;
use App\Support\Site;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $content = Site::data();

        $admin = User::updateOrCreate(
            ['email' => 'awais@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('awais@720'),
                'role' => 'admin',
                'phone' => '+92 300 0000000',
                'email_verified_at' => now(),
            ],
        );

        $doctorData = $content['doctor'];

        $doctor = User::updateOrCreate(
            ['email' => 'ahmed@medicare.test'],
            [
                'name' => $doctorData['name'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => $doctorData['phone'],
                'specialization' => $doctorData['specialization'],
                'bio' => $doctorData['bio'],
                'qualifications' => $doctorData['qualifications'],
                'education' => $doctorData['education'],
                'experience_years' => $doctorData['experience_years'],
                'consultation_fee' => $doctorData['consultation_fee'],
                'rating' => $doctorData['rating'],
                'location' => $doctorData['location'],
                'clinics' => $doctorData['clinics'],
                'available' => $doctorData['available'],
                'email_verified_at' => now(),
            ],
        );

        User::updateOrCreate(
            ['email' => 'patient@medicare.test'],
            [
                'name' => 'Ayesha Khan',
                'password' => Hash::make('password'),
                'role' => 'patient',
                'phone' => '+92 300 1111111',
                'email_verified_at' => now(),
            ],
        );

        foreach ($content['services'] as $service) {
            Service::updateOrCreate(
                ['slug' => $service['slug']],
                [
                    'name' => $service['name'],
                    'description' => $service['description'],
                    'procedures' => $service['procedures'],
                    'icon' => $service['icon'],
                    'duration_minutes' => $service['duration_minutes'],
                    'price' => $service['price'],
                    'consultation_type' => $service['consultation_type'],
                    'is_active' => $service['is_active'],
                    'sort_order' => $service['sort_order'],
                ],
            );
        }
    }
}
