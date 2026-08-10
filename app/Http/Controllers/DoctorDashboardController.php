<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DoctorDashboardController extends Controller
{
    public function index()
    {
        $doctor = auth()->user();

        $today = today();

        $todaysAppointments = $doctor->doctorAppointments()
            ->whereDate('appointment_date', $today)
            ->with(['patient', 'service'])
            ->orderBy('appointment_time')
            ->get()
            ->map(fn (Appointment $a) => $this->serialize($a));

        $pendingRequests = $doctor->doctorAppointments()
            ->where('status', Appointment::STATUS_PENDING)
            ->with(['patient', 'service'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->get()
            ->map(fn (Appointment $a) => $this->serialize($a));

        $hour = now()->hour;

        return Inertia::render('Doctor/Dashboard', [
            'greeting' => $hour < 12 ? 'Good morning' : ($hour < 17 ? 'Good afternoon' : 'Good evening'),
            'stats' => [
                'today' => $doctor->doctorAppointments()->whereDate('appointment_date', $today)->where('status', '!=', 'cancelled')->count(),
                'pending' => $doctor->doctorAppointments()->where('status', 'pending')->count(),
                'available_slots' => 12,
                'completed' => $doctor->doctorAppointments()->where('status', 'completed')->count(),
            ],
            'todaysAppointments' => $todaysAppointments,
            'pendingRequests' => $pendingRequests,
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        abort_unless($appointment->doctor_id === auth()->id(), 403);

        $data = $request->validate([
            'status' => ['required', 'in:confirmed,completed,cancelled'],
        ]);

        $status = $data['status'];

        if ($status === Appointment::STATUS_CANCELLED) {
            $appointment->update([
                'status' => Appointment::STATUS_CANCELLED,
                'payment_status' => 'refunded',
            ]);
        } else {
            $appointment->update([
                'status' => $status,
                'completed_at' => $status === Appointment::STATUS_COMPLETED ? now() : $appointment->completed_at,
            ]);
        }

        $label = match ($status) {
            'confirmed' => 'confirmed',
            'completed' => 'marked as completed',
            default => 'cancelled',
        };

        return back()->with('success', "Appointment {$appointment->reference} {$label}.");
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
            'notes' => $a->notes,
            'patient' => [
                'name' => $a->patient->name,
                'email' => $a->patient->email,
                'phone' => $a->patient->phone,
                'initials' => collect(explode(' ', $a->patient->name))->slice(0, 2)->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))->join(''),
            ],
            'service' => [
                'name' => $a->service->name,
                'icon' => $a->service->icon,
                'duration_minutes' => $a->service->duration_minutes,
            ],
        ];
    }
}
