<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function create()
    {
        $doctor = User::where('role', 'doctor')->first();
        if (! $doctor) {
            return Inertia::render('Home');
        }

        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($service) => $this->serializeService($service));

        $slots = $this->slotsForDate(Carbon::today());

        return Inertia::render('Booking/Index', [
            'doctor' => $this->serializeDoctor($doctor),
            'services' => $services,
            'todaySlots' => $slots,
        ]);
    }

    public function availability(Request $request)
    {
        $request->validate([
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
        ]);

        return response()->json($this->slotsForDate(Carbon::parse($request->date)));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'service_id' => ['required', 'exists:services,id,is_active,1'],
            'consultation_type' => ['required', 'in:online,physical'],
            'date' => ['required', 'date_format:Y-m-d', 'after_or_equal:today'],
            'time' => ['required', 'string'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $doctor = User::where('role', 'doctor')->firstOrFail();
        $service = Service::findOrFail($data['service_id']);

        $slot = collect($this->generateSlots(Carbon::parse($data['date'])))->firstWhere('time', $data['time']);
        if (! $slot || $slot['booked']) {
            return back()->withErrors(['time' => 'That time slot is no longer available. Please choose another time.']);
        }

        $clash = Appointment::where('doctor_id', $doctor->id)
            ->where('appointment_date', $data['date'])
            ->where('appointment_time', $data['time'])
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->exists();

        if ($clash) {
            return back()->withErrors(['time' => 'That time slot has just been booked. Please choose another time.']);
        }

        $appointment = Appointment::create([
            'reference' => 'MC-'.strtoupper(Str::random(6)),
            'user_id' => $request->user()->id,
            'doctor_id' => $doctor->id,
            'service_id' => $service->id,
            'appointment_date' => $data['date'],
            'appointment_time' => $data['time'],
            'consultation_type' => $data['consultation_type'],
            'status' => Appointment::STATUS_CONFIRMED,
            'payment_status' => Appointment::PAYMENT_PAID,
            'amount' => $service->price,
            'notes' => $data['notes'] ?? null,
            'meeting_url' => $data['consultation_type'] === 'online'
                ? 'https://meet.medicare.test/dr-awais/'.strtolower(Str::random(6))
                : null,
        ]);

        return redirect()->route('appointments.show', $appointment);
    }

    public function show(Appointment $appointment)
    {
        abort_unless($appointment->user_id === auth()->id(), 403);

        return Inertia::render('Booking/Success', [
            'appointment' => [
                'id' => $appointment->id,
                'reference' => $appointment->reference,
                'status' => $appointment->status,
                'status_label' => $appointment->status_label,
                'payment_status' => $appointment->payment_status,
                'amount' => $appointment->amount,
                'date' => $appointment->appointment_date->format('Y-m-d'),
                'date_label' => $appointment->appointment_date->format('l, j M Y'),
                'time' => $appointment->appointment_time,
                'time_label' => $appointment->time_label,
                'consultation_type' => $appointment->consultation_type,
                'meeting_url' => $appointment->meeting_url,
                'notes' => $appointment->notes,
                'doctor' => [
                    'name' => $appointment->doctor->name,
                    'specialization' => $appointment->doctor->specialization,
                    'location' => $appointment->doctor->location,
                    'clinics' => $appointment->doctor->clinics ?? [],
                    'phone' => $appointment->doctor->phone,
                ],
                'service' => [
                    'name' => $appointment->service->name,
                    'duration_minutes' => $appointment->service->duration_minutes,
                ],
            ],
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

    private function serializeDoctor(User $doctor): array
    {
        return [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'specialization' => $doctor->specialization,
            'consultation_fee' => $doctor->consultation_fee,
            'rating' => $doctor->rating,
            'location' => $doctor->location,
            'clinics' => $doctor->clinics ?? [],
            'initials' => collect(explode(' ', $doctor->name))->slice(0, 2)->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))->join(''),
        ];
    }

    private function slotsForDate(Carbon $date): array
    {
        return [
            'date' => $date->format('Y-m-d'),
            'slots' => $this->generateSlots($date),
        ];
    }

    private function generateSlots(Carbon $date): array
    {
        $booked = Appointment::where('doctor_id', User::where('role', 'doctor')->first()?->id)
            ->where('appointment_date', $date->format('Y-m-d'))
            ->whereIn('status', [Appointment::STATUS_PENDING, Appointment::STATUS_CONFIRMED])
            ->pluck('appointment_time')
            ->map(fn ($t) => substr($t, 0, 5))
            ->all();

        $slots = [];
        $start = Carbon::createFromTime(9, 0);
        $end = Carbon::createFromTime(17, 30);

        for ($time = $start; $time->lessThanOrEqualTo($end); $time->addMinutes(30)) {
            $timeKey = $time->format('H:i');

            if ($date->isToday() && $time->lessThanOrEqualTo(now()->addMinutes(60))) {
                continue;
            }

            $slots[] = [
                'time' => $timeKey,
                'label' => $time->format('g:i A'),
                'booked' => in_array($timeKey, $booked, true),
            ];
        }

        return $slots;
    }
}
