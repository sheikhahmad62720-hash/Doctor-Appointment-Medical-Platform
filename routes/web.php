<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\PresenceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicController;
use App\Http\Middleware\EnsureUserRole;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/services/{slug}', [PublicController::class, 'serviceDetail'])->name('services.show');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

Route::middleware('auth')->group(function () {
    Route::get('/book', [BookingController::class, 'create'])->name('booking.create');
    Route::get('/book/availability', [BookingController::class, 'availability'])->name('booking.availability');
    Route::post('/book', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/appointments/{appointment}', [BookingController::class, 'show'])->name('appointments.show');

    Route::get('/dashboard', [PatientDashboardController::class, 'index'])->name('dashboard');
    Route::post('/appointments/{appointment}/cancel', [PatientDashboardController::class, 'cancel'])->name('appointments.cancel');

    Route::get('/chat', [ChatController::class, 'patientIndex'])->name('chat.index');
    Route::get('/chat/{conversation}', [ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('/chat/{conversation}', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::post('/chat/{conversation}/attach', [ChatController::class, 'attachFile'])->name('chat.attach');
    Route::post('/chat/{conversation}/read', [ChatController::class, 'markAsRead'])->name('chat.read');
    Route::post('/chat/{conversation}/delivered', [ChatController::class, 'markDelivered'])->name('chat.delivered');
    Route::post('/chat/{conversation}/messages/{message}/delete-me', [ChatController::class, 'deleteForMe'])->name('chat.delete-me');
    Route::post('/chat/{conversation}/messages/{message}/delete-everyone', [ChatController::class, 'deleteForEveryone'])->name('chat.delete-everyone');
    Route::get('/chat/{conversation}/peer-status', [PresenceController::class, 'peerStatus'])->name('chat.peer-status');

    Route::post('/presence/heartbeat', [PresenceController::class, 'heartbeat'])->name('presence.heartbeat');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', EnsureUserRole::class.':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/messages/{contactMessage}/toggle', [ContactController::class, 'toggleRead'])->name('admin.messages.toggle');
    Route::get('/admin/chat', [ChatController::class, 'adminIndex'])->name('admin.chat');
});

require __DIR__.'/auth.php';
