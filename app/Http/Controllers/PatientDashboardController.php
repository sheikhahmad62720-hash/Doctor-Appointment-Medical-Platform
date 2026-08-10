<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $appointments = $user->appointments()
            ->with(['service', 'doctor'])
            ->latest()
            ->get()
            ->map(fn (Appointment $a) => $this->serialize($a));

        $upcoming = $appointments->first(fn ($a) => in_array($a['status'], ['pending', 'confirmed']));

        return Inertia::render('Dashboard/Index', [
            'stats' => [
                'total' => $user->appointments()->count(),
                'upcoming' => $user->appointments()->whereIn('status', ['pending', 'confirmed'])->count(),
                'completed' => $user->appointments()->where('status', 'completed')->count(),
                'pending_payments' => $user->appointments()->where('payment_status', 'pending')->where('status', '!=', 'cancelled')->count(),
            ],
            'upcoming' => $upcoming,
            'appointments' => $appointments,
        ]);
    }

    public function cancel(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->user_id === auth()->id(), 403);

        if (! $appointment->isCancellable()) {
            return back()->with('error', 'This appointment can no longer be cancelled.');
        }

        $appointment->update([
            'status' => Appointment::STATUS_CANCELLED,
            'payment_status' => 'refunded',
        ]);

        return back()->with('success', 'Your appointment has been cancelled. Any payment will be refunded.');
    }

    private function serialize(Appointment $a): array
    {
        return [
            'id' => $a->id,
            'reference' => $a->reference,
            'status' => $a->status,
            'status_label' => $a->status_label,
            'payment_status' => $a->payment_status,
            'amount' => $a->amount,
            'date' => $a->appointment_date->format('Y-m-d'),
            'date_label' => $a->appointment_date->format('D, j M'),
            'time' => $a->appointment_time,
            'time_label' => $a->time_label,
            'consultation_type' => $a->consultation_type,
            'meeting_url' => $a->meeting_url,
            'cancellable' => $a->isCancellable(),
            'service' => [
                'name' => $a->service->name,
                'icon' => $a->service->icon,
                'duration_minutes' => $a->service->duration_minutes,
            ],
            'doctor' => [
                'name' => $a->doctor->name,
                'specialization' => $a->doctor->specialization,
                'location' => $a->doctor->location,
                'clinics' => $a->doctor->clinics ?? [],
                'initials' => collect(explode(' ', $a->doctor->name))->slice(0, 2)->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))->join(''),
            ],
        ];
    }
}
