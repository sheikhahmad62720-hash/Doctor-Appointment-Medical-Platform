<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'slug',
    'description',
    'procedures',
    'icon',
    'duration_minutes',
    'price',
    'consultation_type',
    'is_active',
    'sort_order',
])]
class Service extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'float',
            'is_active' => 'boolean',
            'procedures' => 'array',
        ];
    }

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    public function getDurationLabelAttribute(): string
    {
        return "{$this->duration_minutes} min";
    }

    public function getPriceLabelAttribute(): string
    {
        return 'Rs '.number_format($this->price, $this->price == floor($this->price) ? 0 : 2);
    }

    public function getConsultationTypeLabelAttribute(): string
    {
        return match ($this->consultation_type) {
            'online' => 'Online',
            'physical' => 'In Clinic',
            default => 'Online & In Clinic',
        };
    }
}
