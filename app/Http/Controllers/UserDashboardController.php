<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use App\Models\Traveler;
use App\Models\UserNotification;
use App\Models\UserVoucher;
use App\Models\Voucher;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserDashboardController extends Controller
{
    private function bookingsOf(Request $request)
    {
        $user = $request->user();

        return Booking::query()->with('package:id,title')
            ->where(function ($q) use ($user): void {
                $q->where('user_id', $user->id)->orWhere('guest_email', $user->email);
            });
    }

    public function dashboard(Request $request)
    {
        $bookings = $this->bookingsOf($request)->orderByDesc('id')->limit(3)->get();
        $all = $this->bookingsOf($request);

        return response()->json([
            'stats' => [
                'finished' => (clone $all)->where('status', 'paid')->count(),
                'pending_payment' => (clone $all)->where('payment_status', 'unpaid')->count(),
                'vouchers' => UserVoucher::where('user_id', $request->user()->id)->where('state', 'aktif')->count(),
            ],
            'bookings' => $bookings,
        ]);
    }

    public function bookings(Request $request)
    {
        $query = $this->bookingsOf($request)->orderByDesc('id');
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        return $query->paginate($request->integer('per_page', 10));
    }

    public function bookingDetail(Request $request, string $code)
    {
        $booking = $this->bookingsOf($request)->where('code', $code)->firstOrFail();

        return $booking->load('package');
    }

    public function cancel(Request $request, string $code)
    {
        $booking = $this->bookingsOf($request)->where('code', $code)->firstOrFail();
        $data = $request->validate(['reason' => ['nullable', 'string']]);
        $booking->update(['status' => 'cancelled', 'notes' => $data['reason'] ?? $booking->notes]);

        return $booking->load('package');
    }

    public function reschedule(Request $request, string $code)
    {
        $booking = $this->bookingsOf($request)->where('code', $code)->firstOrFail();
        $data = $request->validate(['departure_date' => ['required', 'date', 'after_or_equal:today']]);
        $booking->update(['departure_date' => $data['departure_date'], 'status' => 'pending']);

        return $booking->load('package');
    }

    public function payments(Request $request)
    {
        return $this->bookingsOf($request)->orderByDesc('id')->limit(20)->get(['code', 'total', 'payment_status', 'status', 'created_at']);
    }

    public function paymentProof(Request $request, string $code)
    {
        $booking = $this->bookingsOf($request)->where('code', $code)->firstOrFail();
        $data = $request->validate([
            'method' => ['required', 'string', 'max:30'],
            'proof' => ['nullable', 'string', 'max:255'],
        ]);
        $booking->update(['payment_status' => 'dp', 'notes' => 'Bukti via '.$data['method'].($booking->notes ? ' | '.$booking->notes : '')]);

        return $booking->load('package');
    }

    public function wishlist(Request $request)
    {
        return Wishlist::query()->with('package')->where('user_id', $request->user()->id)->orderByDesc('id')->get();
    }

    public function toggleWishlist(Request $request)
    {
        $data = $request->validate(['package_id' => ['required', 'integer', 'exists:packages,id']]);
        $existing = Wishlist::where('user_id', $request->user()->id)->where('package_id', $data['package_id'])->first();
        if ($existing) {
            $existing->delete();

            return response()->json(['saved' => false]);
        }
        Wishlist::create(['user_id' => $request->user()->id, 'package_id' => $data['package_id']]);

        return response()->json(['saved' => true], 201);
    }

    public function vouchers(Request $request)
    {
        return UserVoucher::query()->with('voucher')->where('user_id', $request->user()->id)->orderByDesc('id')->get();
    }

    public function claimVoucher(Request $request)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:30']]);
        $voucher = Voucher::where('code', strtoupper(trim($data['code'])))->where('is_active', true)->firstOrFail();
        $claimed = UserVoucher::firstOrCreate(
            ['user_id' => $request->user()->id, 'voucher_id' => $voucher->id],
            ['state' => 'aktif']
        );

        return response()->json($claimed->load('voucher'), 201);
    }

    public function reviews(Request $request)
    {
        return Review::where('user_id', $request->user()->id)->orderByDesc('id')->get();
    }

    public function storeReview(Request $request)
    {
        $data = $request->validate([
            'booking_code' => ['nullable', 'string', 'max:30'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'review' => ['nullable', 'string'],
        ]);
        $booking = $data['booking_code'] ?? null
            ? $this->bookingsOf($request)->where('code', $data['booking_code'])->first()
            : null;
        $review = Review::create([
            'user_id' => $request->user()->id,
            'booking_id' => $booking?->id,
            'booking_code' => $data['booking_code'] ?? null,
            'rating' => $data['rating'],
            'review' => $data['review'] ?? null,
        ]);

        return response()->json($review, 201);
    }

    public function notifications(Request $request)
    {
        return UserNotification::where('user_id', $request->user()->id)->orderByDesc('id')->limit(50)->get();
    }

    public function readNotification(Request $request, int $id)
    {
        $notification = UserNotification::where('user_id', $request->user()->id)->findOrFail($id);
        $notification->update(['read_at' => now()]);

        return $notification;
    }

    public function profile(Request $request)
    {
        return $request->user();
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->user()->id)],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);
        $request->user()->update($data);

        return $request->user();
    }

    public function travelers(Request $request)
    {
        return Traveler::where('user_id', $request->user()->id)->orderBy('id')->get();
    }

    public function storeTraveler(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'id_number' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:30'],
            'tag' => ['nullable', 'string', 'max:50'],
        ]);
        $traveler = Traveler::create([...$data, 'user_id' => $request->user()->id]);

        return response()->json($traveler, 201);
    }

    public function destroyTraveler(Request $request, int $id)
    {
        $traveler = Traveler::where('user_id', $request->user()->id)->findOrFail($id);
        $traveler->delete();

        return response()->noContent();
    }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current' => ['required', 'string'],
            'next' => ['required', 'string', 'min:8'],
        ]);
        abort_unless(Hash::check($data['current'], $request->user()->password), 422, 'Kata sandi saat ini salah.');
        $request->user()->update(['password' => Hash::make($data['next'])]);

        return response()->json(['message' => 'Kata sandi berhasil diganti.']);
    }
}
