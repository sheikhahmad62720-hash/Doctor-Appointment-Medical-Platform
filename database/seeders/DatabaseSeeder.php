<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@medicare.test'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+92 300 0000000',
                'email_verified_at' => now(),
            ],
        );

        $doctor = User::updateOrCreate(
            ['email' => 'ahmed@medicare.test'],
            [
                'name' => 'Prof. Dr. Awais Malik',
                'password' => Hash::make('password'),
                'role' => 'doctor',
                'phone' => '+92 300 1234567',
                'specialization' => 'Bariatric, Laparoscopic & General Surgeon',
                'bio' => 'Prof. Dr. Awais Malik is a Professor of Surgery at Fatima Memorial Hospital, Lahore, with more than 25 years of surgical experience. He specialises in bariatric (weight loss) surgery, advanced laparoscopic procedures and complex general surgery — combining technical precision with calm, patient-first care.',
                'qualifications' => "MBBS\nFCPS (General Surgery)",
                'education' => 'Fatima Memorial Hospital, Lahore',
                'experience_years' => 28,
                'consultation_fee' => 3000,
                'rating' => 4.9,
                'location' => 'Fatima Memorial Hospital, Shadman, Lahore',
                'clinics' => [
                    [
                        'name' => 'Fatima Memorial Hospital (FMH)',
                        'area' => 'Shadman',
                        'city' => 'Lahore',
                        'timing' => 'Morning',
                        'address' => 'Fatima Memorial Hospital, Shadman, Lahore',
                    ],
                    [
                        'name' => 'Mid City Hospital',
                        'area' => 'Jail Road',
                        'city' => 'Lahore',
                        'timing' => 'Evening',
                        'address' => 'Mid City Hospital, Jail Road, Lahore',
                    ],
                ],
                'available' => true,
                'email_verified_at' => now(),
            ],
        );

        $patients = [
            ['name' => 'Ayesha Khan', 'email' => 'patient@medicare.test'],
            ['name' => 'Ali Raza', 'email' => 'michael@example.com'],
            ['name' => 'Fatima Malik', 'email' => 'emily@example.com'],
            ['name' => 'Hassan Ahmed', 'email' => 'david@example.com'],
            ['name' => 'Sana Tariq', 'email' => 'priya@example.com'],
        ];

        foreach ($patients as $index => $patient) {
            User::updateOrCreate(
                ['email' => $patient['email']],
                [
                    'name' => $patient['name'],
                    'password' => Hash::make('password'),
                    'role' => 'patient',
                    'phone' => '+92 3'.str_pad((string) $index, 2, '0', STR_PAD_LEFT).' 1234567',
                    'email_verified_at' => now(),
                ],
            );
        }

        $services = [
            ['Bariatric Surgery (Weight Loss)', 'bariatric-surgery', 'Surgical weight-loss solutions for obesity and metabolic diseases.', ['Sleeve Gastrectomy', 'Gastric Bypass', 'Treatment of obesity & metabolic diseases'], 'scale', 45, 3000, 'physical', 1],
            ['Laparoscopic Surgery (Minimally Invasive)', 'laparoscopic-surgery', 'Minimally invasive surgery through tiny incisions — less pain, faster recovery and minimal scarring.', ['Gallbladder (gall stone) surgery', 'Hernia repair (TAPP technique)', 'Appendix surgery', 'Acid reflux / hiatal hernia (Fundoplication)'], 'shield', 45, 2500, 'physical', 2],
            ['General Surgery', 'general-surgery', 'Comprehensive surgical care for colorectal, thyroid and breast conditions.', ['Colorectal surgery (piles & fissures)', 'Thyroid surgery', 'Breast surgery'], 'scissors', 30, 2000, 'physical', 3],
        ];

        foreach ($services as $sort => [$name, $slug, $description, $procedures, $icon, $duration, $price, $type]) {
            Service::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'description' => $description,
                    'procedures' => $procedures,
                    'icon' => $icon,
                    'duration_minutes' => $duration,
                    'price' => $price,
                    'consultation_type' => $type,
                    'is_active' => true,
                    'sort_order' => $sort,
                ],
            );
        }

        if (Review::count() === 0) {
            $this->seedAppointments($doctor);
        }

        $curatedReviews = [
            ['Prof. Dr. Awais Malik is incredibly thorough and compassionate. He took the time to explain everything clearly. Highly recommended.', 5],
            ['Excellent surgeon. My laparoscopic surgery went smoothly and recovery was quick.', 5],
            ['Very professional and caring. The clinic is clean and the staff are friendly.', 5],
            ['Great experience from booking to consultation. Very efficient process.', 4],
            ['Prof. Malik helped me recover from my surgery better than anyone before. Truly grateful.', 5],
            ['Knowledgeable, patient and kind. Booking an appointment was very easy.', 5],
            ['Quick appointment, clear advice and reasonable pricing.', 4],
            ['A surgeon who genuinely listens. My follow-up care has been excellent.', 5],
        ];

        foreach ($curatedReviews as $index => [$comment, $rating]) {
            $reviewer = User::where('email', $patients[$index % count($patients)]['email'])->first();
            if (Review::where('user_id', $reviewer->id)->where('comment', $comment)->exists()) {
                continue;
            }
            Review::create([
                'user_id' => $reviewer->id,
                'doctor_id' => $doctor->id,
                'rating' => $rating,
                'comment' => $comment,
                'is_published' => true,
            ]);
        }

        $doctor->forceFill(['rating' => 4.9])->save();
    }

    private function seedAppointments(User $doctor): void
    {
        $patient = User::where('email', 'patient@medicare.test')->first();
        $others = User::whereIn('email', ['michael@example.com', 'emily@example.com', 'david@example.com', 'priya@example.com'])->get();
        $serviceIds = Service::pluck('id')->all();

        $demo = [
            ['status' => 'confirmed', 'daysAhead' => 0, 'time' => '09:30', 'type' => 'physical', 'payment' => 'paid'],
            ['status' => 'confirmed', 'daysAhead' => 0, 'time' => '11:00', 'type' => 'physical', 'payment' => 'paid'],
            ['status' => 'pending', 'daysAhead' => 0, 'time' => '15:30', 'type' => 'physical', 'payment' => 'pending'],
            ['status' => 'confirmed', 'daysAhead' => 0, 'time' => '17:00', 'type' => 'physical', 'payment' => 'pending'],
            ['status' => 'confirmed', 'daysAhead' => 1, 'time' => '10:00', 'type' => 'physical', 'payment' => 'pending'],
            ['status' => 'pending', 'daysAhead' => 2, 'time' => '14:00', 'type' => 'physical', 'payment' => 'pending'],
            ['status' => 'completed', 'daysAhead' => -6, 'time' => '10:30', 'type' => 'physical', 'payment' => 'paid'],
            ['status' => 'completed', 'daysAhead' => -10, 'time' => '16:00', 'type' => 'physical', 'payment' => 'paid'],
            ['status' => 'completed', 'daysAhead' => -20, 'time' => '09:00', 'type' => 'physical', 'payment' => 'paid'],
            ['status' => 'cancelled', 'daysAhead' => -3, 'time' => '13:30', 'type' => 'physical', 'payment' => 'refunded'],
        ];

        $seedServiceIndex = 0;

        foreach ($demo as $index => $item) {
            $date = now()->addDays($item['daysAhead']);
            $time = $item['time'];
            $owner = $index === 0 ? $patient : $others[$index % $others->count()];
            $serviceId = $serviceIds[$seedServiceIndex % count($serviceIds)];
            $service = Service::find($serviceId);

            $appointment = Appointment::create([
                'reference' => 'MC-'.strtoupper(Str::random(6)),
                'user_id' => $owner->id,
                'doctor_id' => $doctor->id,
                'service_id' => $serviceId,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'consultation_type' => $item['type'],
                'status' => $item['status'],
                'payment_status' => $item['payment'],
                'amount' => $service?->price ?? 2500,
                'notes' => $index === 0 ? 'Post-surgery follow-up for bariatric consultation.' : null,
                'meeting_url' => null,
                'completed_at' => $item['status'] === 'completed' ? $date->setTimeFromTimeString($time)->addMinutes(30) : null,
            ]);

            $seedServiceIndex++;

            if ($item['status'] === 'completed') {
                Review::create([
                    'user_id' => $owner->id,
                    'doctor_id' => $doctor->id,
                    'appointment_id' => $appointment->id,
                    'rating' => 5,
                    'comment' => 'Great consultation, very clear advice.',
                    'is_published' => true,
                ]);
            }
        }
    }
}
