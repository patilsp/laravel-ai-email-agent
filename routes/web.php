<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailActionController;
use App\Http\Controllers\GoogleAuthController;
use App\Models\User;
use Database\Seeders\DemoDataSeeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Marketing & Interactive Workbench
Route::get('/', function () {
    return view('marketing.index');
})->name('home');

// Authentication & Login Routes
Route::get('/login', [GoogleAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [GoogleAuthController::class, 'login'])->name('login.submit');
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// Local Development / Demo Sandbox Login
Route::get('/dev-login', function () {
    $user = User::firstOrCreate(
        ['email' => 'demo@ai-email-agent.local'],
        [
            'name' => 'Executive Demo User',
            'password' => bcrypt('password'),
        ]
    );

    if ($user->emailMessages()->count() === 0) {
        DemoDataSeeder::seedUser($user);
    }

    Auth::login($user, remember: true);

    return redirect()->route('dashboard')->with('status', 'Welcome to the AI Email Agent Workspace! Sample triage emails loaded.');
})->name('dev.login');

// Authenticated Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/sync', [DashboardController::class, 'sync'])->name('dashboard.sync');
    Route::post('/auth/google/disconnect', [GoogleAuthController::class, 'disconnect'])->name('auth.google.disconnect');
    Route::post('/logout', [GoogleAuthController::class, 'logout'])->name('logout');

    // Email Human-in-the-Loop & Management Actions
    Route::post('/drafts/{draft}/approve', [EmailActionController::class, 'approve'])->name('drafts.approve');
    Route::put('/drafts/{draft}', [EmailActionController::class, 'edit'])->name('drafts.edit');
    Route::post('/drafts/{draft}/reject', [EmailActionController::class, 'reject'])->name('drafts.reject');
    Route::post('/emails/{emailMessage}/labels', [EmailActionController::class, 'applyLabels'])->name('emails.labels');
    Route::delete('/emails/{emailMessage}', [EmailActionController::class, 'trash'])->name('emails.trash');
    Route::post('/emails/send', [EmailActionController::class, 'sendCustom'])->name('emails.send');
});
