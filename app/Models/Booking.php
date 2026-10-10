<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'package_id', 'user_id', 'guest_name', 'guest_email', 'guest_phone',
        'departure_date', 'adults', 'children', 'subtotal', 'discount',
        'total', 'status', 'payment_status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'departure_date' => 'date',
            'adults' => 'integer',
            'children' => 'integer',
            'subtotal' => 'integer',
            'discount' => 'integer',
            'total' => 'integer',
        ];
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking): void {
            if (empty($booking->code)) {
                $booking->code = 'JN-'.now()->format('Y').'-'.strtoupper(Str::random(6));
            }
        });
    }
}
