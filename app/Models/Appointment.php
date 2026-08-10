<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'reference',
    'user_id',
    'doctor_id',
    'service_id',
    'appointment_date',
    'appointment_time',
    'consultation_type',
    'status',
    'payment_status',
    'amount',
    'notes',
    'meeting_url',
    'completed_at',
])]
class Appointment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_PAID = 'paid';

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'appointment_time' => 'string',
            'completed_at' => 'datetime',
            'amount' => 'float',
        ];
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_CONFIRMED], true)
            && $this->appointment_date->isFuture();
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function getTimeLabelAttribute(): string
    {
        return \Carbon\Carbon::parse($this->appointment_time)->format('g:i A');
    }

    public function getDateLabelAttribute(): string
    {
        return $this->appointment_date?->format('j M Y');
    }
}
