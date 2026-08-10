<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Service;
use App\Models\User;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function home()
    {
        $doctor = User::where('role', 'doctor')->first();

        $doctorData = $this->serializeDoctor($doctor);

        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->take(6)
            ->get()
            ->map(fn (Service $service) => $this->serializeService($service));

        $reviews = Review::where('doctor_id', $doctor->id)
            ->where('is_published', true)
            ->latest()
            ->take(3)
            ->get()
            ->map(fn (Review $review) => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'patient' => $review->user->name,
                'date' => $review->created_at->format('M Y'),
            ]);

        return Inertia::render('Home', [
            'doctor' => $doctorData,
            'services' => $services,
            'reviews' => $reviews,
        ]);
    }

    public function about()
    {
        $doctor = $this->doctor();

        return Inertia::render('About', [
            'doctor' => $doctor,
        ]);
    }

    public function services()
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (Service $service) => $this->serializeService($service));

        return Inertia::render('Services', [
            'services' => $services,
            'doctor' => $this->doctor(),
        ]);
    }

    public function contact()
    {
        return Inertia::render('Contact', [
            'doctor' => $this->doctor(),
        ]);
    }

    private function serializeService(Service $service): array
    {
        return [
            'id' => $service->id,
            'name' => $service->name,
            'slug' => $service->slug,
            'description' => $service->description,
            'procedures' => $service->procedures ?? [],
            'icon' => $service->icon,
            'duration_minutes' => $service->duration_minutes,
            'duration_label' => $service->duration_label,
            'price' => $service->price,
            'price_label' => $service->price_label,
            'consultation_type' => $service->consultation_type,
            'consultation_type_label' => $service->consultation_type_label,
        ];
    }

    private function serializeDoctor(?User $doctor): ?array
    {
        if (! $doctor) {
            return null;
        }

        return [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'email' => $doctor->email,
            'phone' => $doctor->phone,
            'specialization' => $doctor->specialization,
            'bio' => $doctor->bio,
            'qualifications' => $doctor->qualifications,
            'education' => $doctor->education,
            'experience_years' => $doctor->experience_years,
            'consultation_fee' => $doctor->consultation_fee,
            'rating' => $doctor->rating,
            'location' => $doctor->location,
            'clinics' => $doctor->clinics ?? [],
            'available' => $doctor->available,
            'initials' => collect(explode(' ', $doctor->name))->slice(0, 2)->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))->join(''),
        ];
    }

    private function doctor(): array
    {
        return $this->serializeDoctor(User::where('role', 'doctor')->first());
    }
}
