<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ContactMessageController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\UserDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    // Public catalog
    Route::get('packages', [PackageController::class, 'index']);
    Route::get('packages/featured', [PackageController::class, 'featured']);
    Route::get('articles', [ArticleController::class, 'index']);
    Route::get('testimonials', [TestimonialController::class, 'index']);
    Route::get('faqs', [FaqController::class, 'index']);
    Route::get('gallery', [GalleryController::class, 'index']);
    Route::get('team', [AdminController::class, 'publicTeam']);
    Route::get('settings', [AdminController::class, 'settings']);
    Route::get('articles/{article:slug}', [ArticleController::class, 'show']);
    Route::get('packages/{package:slug}', [PackageController::class, 'show']);

    // Public write (stateless, validated, rate-limited against spam)
    Route::post('bookings', [BookingController::class, 'store'])->middleware('throttle:10,1');
    Route::post('contact', [ContactMessageController::class, 'store'])->middleware('throttle:10,1');
});

// Session-guarded routes use web middleware (cookies + CSRF, no new deps).
Route::middleware('web')->prefix('v1')->group(function (): void {
    // Session auth (same guard as web)
    Route::post('auth/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember' => ['sometimes', 'boolean'],
        ]);
        if (! Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $credentials['remember'] ?? false)) {
            return response()->json(['message' => 'Email atau kata sandi salah.'], 422);
        }
        $request->session()->regenerate();

        return $request->user();
    })->middleware('throttle:5,1');
    Route::post('auth/logout', function (Request $request) {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    })->middleware('auth');

    // Admin (session-guarded)
    Route::middleware('auth')->group(function (): void {
        Route::get('auth/me', fn (Request $request) => $request->user());
        Route::get('admin/packages', [PackageController::class, 'adminIndex']);
        Route::post('admin/packages', [PackageController::class, 'store']);
        Route::put('admin/packages/{package}', [PackageController::class, 'update']);
        Route::delete('admin/packages/{package}', [PackageController::class, 'destroy']);
        Route::get('admin/bookings', [BookingController::class, 'index']);
        Route::get('admin/bookings/{booking}', [BookingController::class, 'show']);
        Route::put('admin/bookings/{booking}', [BookingController::class, 'update']);
        Route::get('admin/articles', [ArticleController::class, 'adminIndex']);
        Route::post('admin/articles', [ArticleController::class, 'store']);
        Route::put('admin/articles/{article}', [ArticleController::class, 'update']);
        Route::delete('admin/articles/{article}', [ArticleController::class, 'destroy']);
        Route::get('admin/testimonials', [TestimonialController::class, 'adminIndex']);
        Route::post('admin/testimonials', [TestimonialController::class, 'store']);
        Route::put('admin/testimonials/{testimonial}', [TestimonialController::class, 'update']);
        Route::delete('admin/testimonials/{testimonial}', [TestimonialController::class, 'destroy']);
        Route::get('admin/faqs', [FaqController::class, 'adminIndex']);
        Route::post('admin/faqs', [FaqController::class, 'store']);
        Route::put('admin/faqs/{faq}', [FaqController::class, 'update']);
        Route::delete('admin/faqs/{faq}', [FaqController::class, 'destroy']);
        Route::get('admin/gallery', [GalleryController::class, 'adminIndex']);
        Route::post('admin/gallery', [GalleryController::class, 'store']);
        Route::put('admin/gallery/{item}', [GalleryController::class, 'update']);
        Route::delete('admin/gallery/{item}', [GalleryController::class, 'destroy']);
        Route::get('admin/messages', [ContactMessageController::class, 'index']);
        Route::put('admin/messages/{message}', [ContactMessageController::class, 'update']);
        Route::get('admin/dashboard', [AdminController::class, 'dashboard']);
        Route::get('admin/settings', [AdminController::class, 'settings']);
        Route::post('admin/settings', [AdminController::class, 'saveSettings']);
        Route::get('admin/team', [AdminController::class, 'team']);
        Route::post('admin/team', [AdminController::class, 'storeTeam']);
        Route::put('admin/team/{member}', [AdminController::class, 'updateTeam']);
        Route::delete('admin/team/{member}', [AdminController::class, 'destroyTeam']);
        Route::get('admin/users', [AdminController::class, 'users']);
        Route::put('admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::get('admin/profile', [AdminController::class, 'profile']);
        Route::put('admin/profile', [AdminController::class, 'updateProfile']);
        Route::get('admin/audit', [AdminController::class, 'audit']);
        Route::post('admin/uploads', [UploadController::class, 'store'])->middleware('throttle:20,1');

        // User dashboard (session-guarded, self-scoped)
        Route::prefix('me')->group(function (): void {
            Route::get('dashboard', [UserDashboardController::class, 'dashboard']);
            Route::get('bookings', [UserDashboardController::class, 'bookings']);
            Route::get('bookings/{code}', [UserDashboardController::class, 'bookingDetail']);
            Route::post('bookings/{code}/cancel', [UserDashboardController::class, 'cancel']);
            Route::post('bookings/{code}/reschedule', [UserDashboardController::class, 'reschedule']);
            Route::get('payments', [UserDashboardController::class, 'payments']);
            Route::post('bookings/{code}/payment-proof', [UserDashboardController::class, 'paymentProof']);
            Route::get('wishlist', [UserDashboardController::class, 'wishlist']);
            Route::post('wishlist/toggle', [UserDashboardController::class, 'toggleWishlist']);
            Route::get('vouchers', [UserDashboardController::class, 'vouchers']);
            Route::post('vouchers/claim', [UserDashboardController::class, 'claimVoucher']);
            Route::get('reviews', [UserDashboardController::class, 'reviews']);
            Route::post('reviews', [UserDashboardController::class, 'storeReview']);
            Route::get('notifications', [UserDashboardController::class, 'notifications']);
            Route::post('notifications/{id}/read', [UserDashboardController::class, 'readNotification']);
            Route::get('profile', [UserDashboardController::class, 'profile']);
            Route::put('profile', [UserDashboardController::class, 'updateProfile']);
            Route::get('travelers', [UserDashboardController::class, 'travelers']);
            Route::post('travelers', [UserDashboardController::class, 'storeTraveler']);
            Route::delete('travelers/{id}', [UserDashboardController::class, 'destroyTraveler']);
            Route::put('password', [UserDashboardController::class, 'changePassword']);
        });
    });
});
