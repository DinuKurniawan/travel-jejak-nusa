<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    private const PROMO_DISCOUNTS = [
        'KOMODOHEMAT' => 200000,
        'JEJAKNUSA' => 200000,
    ];

    public function store(Request $request)
    {
        $data = $request->validate([
            'package_id' => ['required', 'integer', 'exists:packages,id'],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:30'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'adults' => ['required', 'integer', 'min:1', 'max:10'],
            'children' => ['sometimes', 'integer', 'min:0', 'max:6'],
            'promo_code' => ['sometimes', 'nullable', 'string', 'max:30'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ]);

        $package = Package::findOrFail($data['package_id']);
        abort_if($package->status !== 'published', 422, 'Paket tidak tersedia.');

        $children = $data['children'] ?? 0;
        $subtotal = $data['adults'] * $package->price + $children * (int) round($package->price * 0.8);
        $discount = self::PROMO_DISCOUNTS[strtoupper(trim($data['promo_code'] ?? ''))] ?? 0;

        $booking = Booking::create([
            'package_id' => $package->id,
            'guest_name' => $data['guest_name'],
            'guest_email' => $data['guest_email'],
            'guest_phone' => $data['guest_phone'],
            'departure_date' => $data['departure_date'],
            'adults' => $data['adults'],
            'children' => $children,
            'subtotal' => $subtotal,
            'discount' => min($discount, $subtotal),
            'total' => $subtotal - min($discount, $subtotal),
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json($booking->load('package'), 201);
    }

    public function index()
    {
        return Booking::query()->with('package:id,title')->orderByDesc('id')->paginate(20);
    }

    public function show(Booking $booking)
    {
        return $booking->load('package');
    }

    public function update(Request $request, Booking $booking)
    {
        $data = $request->validate([
            'status' => ['sometimes', 'string', Rule::in(['pending', 'verified', 'paid', 'cancelled'])],
            'payment_status' => ['sometimes', 'string', Rule::in(['unpaid', 'dp', 'paid'])],
            'notes' => ['sometimes', 'nullable', 'string'],
        ]);
        $booking->update($data);

        return $booking->load('package');
    }
}
