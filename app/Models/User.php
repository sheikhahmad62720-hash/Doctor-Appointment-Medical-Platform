<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
#[Fillable([
    'name',
    'email',
    'password',
    'role',
    'phone',
    'specialization',
    'bio',
    'qualifications',
    'education',
    'experience_years',
    'consultation_fee',
    'rating',
    'location',
    'clinics',
    'available',
    'last_seen_at',
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

        public const ROLE_PATIENT = 'patient';
        public const ROLE_DOCTOR = 'doctor';
        public const ROLE_ADMIN = 'admin';

        protected function casts(): array
        {
            return [
                'email_verified_at' => 'datetime',
                'last_seen_at' => 'datetime',
                'password' => 'hashed',
                'available' => 'boolean',
                'clinics' => 'array',
            ];
        }

    public function isDoctor(): bool
    {
        return $this->role === self::ROLE_DOCTOR;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'user_id');
    }

    public function doctorAppointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'doctor_id');
    }

    public function conversationsAsPatient(): HasMany
    {
        return $this->hasMany(Conversation::class, 'patient_id');
    }

    public function conversationsAsAdmin(): HasMany
    {
        return $this->hasMany(Conversation::class, 'admin_id');
    }
}
