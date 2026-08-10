<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRevenue = Appointment::where('payment_status', 'paid')->sum('amount');
        $monthRevenue = Appointment::where('payment_status', 'paid')
            ->whereMonth('appointment_date', now()->month)
            ->whereYear('appointment_date', now()->year)
            ->sum('amount');

        $trend = collect(range(6, 0))->map(function ($i) {
            $date = now()->subDays($i);
            return [
                'label' => $date->format('D'),
                'appointments' => Appointment::whereDate('appointment_date', $date)->count(),
                'revenue' => Appointment::whereDate('appointment_date', $date)->where('payment_status', 'paid')->sum('amount'),
            ];
        });

        $statusCounts = Appointment::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $recentAppointments = Appointment::with(['patient', 'service', 'doctor'])
            ->latest()
            ->take(8)
            ->get()
            ->map(fn (Appointment $a) => [
                'id' => $a->id,
                'reference' => $a->reference,
                'status' => $a->status,
                'status_label' => $a->status_label,
                'amount' => $a->amount,
                'date_label' => $a->appointment_date->format('j M'),
                'time_label' => $a->time_label,
                'patient' => ['name' => $a->patient->name],
                'service' => ['name' => $a->service->name],
            ]);

        $recentUsers = User::where('role', 'patient')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'joined' => $u->created_at->format('j M Y'),
                'initials' => collect(explode(' ', $u->name))->slice(0, 2)->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))->join(''),
            ]);

        $recentReviews = Review::with('user')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn (Review $r) => [
                'id' => $r->id,
                'rating' => $r->rating,
                'comment' => $r->comment,
                'patient' => $r->user->name,
                'date' => $r->created_at->format('j M Y'),
            ]);

        $doctor = User::where('role', 'doctor')->first();

        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'appointments' => Appointment::count(),
                'patients' => User::where('role', 'patient')->count(),
                'doctors' => User::where('role', 'doctor')->count(),
                'revenue' => $totalRevenue,
                'month_revenue' => $monthRevenue,
                'pending_payments' => Appointment::where('payment_status', 'pending')->where('status', '!=', 'cancelled')->sum('amount'),
            ],
            'statusCounts' => $statusCounts,
            'trend' => $trend,
            'recentAppointments' => $recentAppointments,
            'recentUsers' => $recentUsers,
            'recentReviews' => $recentReviews,
            'doctor' => $doctor ? [
                'name' => $doctor->name,
                'specialization' => $doctor->specialization,
                'rating' => $doctor->rating,
                'experience_years' => $doctor->experience_years,
                'initials' => collect(explode(' ', $doctor->name))->slice(0, 2)->map(fn ($w) => strtoupper(mb_substr($w, 0, 1)))->join(''),
            ] : null,
        ]);
    }
}
