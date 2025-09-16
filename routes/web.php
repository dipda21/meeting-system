<?php

use App\Exports\UsersExport;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\MeetingMinuteController;
use App\Http\Controllers\MeetingMinuteExportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

// Redirect root URL
Route::get('/', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('login');
});

Auth::routes();

// Protected Routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/meeting-minutes/export', [MeetingMinuteExportController::class, 'export'])->name('meeting-minutes.export');
    Route::get('/meetings/export', [MeetingController::class, 'export'])->name('meetings.export');
    // Meeting & Notulen
    Route::resource('meetings', MeetingController::class);

    Route::resource('meeting-minutes', MeetingMinuteController::class);
    Route::get('/meeting-minutes/create', [MeetingMinuteController::class, 'create'])->name('meeting-minutes.create');

    Route::get('/meeting-minutes/{id}/preview', [MeetingMinuteController::class, 'preview'])
        ->name('meeting-minutes.preview');

    Route::get('/meeting-minutes/{id}/download', [MeetingMinuteController::class, 'download'])
        ->name('meeting-minutes.download');

    // WhatsApp Integration
    Route::post('/meetings/{meeting}/send-reminder', [MeetingController::class, 'sendReminder'])->name('meetings.send-reminder');
    Route::post('/meetings/{meeting}/send-custom-message', [MeetingController::class, 'sendCustomMessage'])->name('meetings.send-custom-message');
    Route::post('/whatsapp/test', [MeetingController::class, 'testWhatsApp'])->name('whatsapp.test');
    Route::post('/meetings/{id}/approve', [MeetingController::class, 'approve'])->name('meetings.approve');
    Route::post('/meetings/{id}/reject', [MeetingController::class, 'reject'])->name('meetings.reject');

    // WhatsApp Webhook
    Route::get('/whatsapp/webhook', [WhatsAppWebhookController::class, 'verify']);
    Route::post('/whatsapp/webhook', [WhatsAppWebhookController::class, 'handle']);

    // User Export (non-admin)
    Route::get('/export-users', function () {
        return Excel::download(new UsersExport, 'users.xlsx');
    });

    // Admin-only Routes
    Route::middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::post('users/{id}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users-search', [UserController::class, 'search'])->name('users.search');
        Route::get('users-export', [UserController::class, 'export'])->name('users.export');
    });
});

// Redirect after login
Route::get('/home', [DashboardController::class, 'index'])->name('home');
