<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\CustomPasswordResetLinkController;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// Admin
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // Users
    Route::get('/users', [UserController::class, 'index'])->name('admin.user.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.user.edit');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('admin.user.update');
    Route::delete('/users/{user}', [UserController::class, 'delete'])->name('admin.user.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::post('/forgot-password', [CustomPasswordResetLinkController::class, 'store'])
    ->middleware(['guest'])
    ->name('password.email');

Route::get('/password-sent', function () {
    return view('auth.password-sent');
})->middleware(['guest'])->name('password.sent');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/email/change/verify', function (Request $request) {
    $user = User::findOrFail($request->id);

    // Compare the SHA1 hash of the pending_email with the hash from the request
    if (sha1($user->pending_email) === $request->hash) {
        $user->forceFill([
            'email' => $user->pending_email,
            'pending_email' => null,
        ])->save();

        $user->sendEmailChangeSuccessNotification($user->email);

        return redirect()->route('profile.edit')->with('status', 'Email updated successfully!');
    }

    abort(403, 'Invalid verification link.');
})->name('email.change.verify');

// OAuth
Route::get('/oauth/{provider}/redirect', function ($provider) {
    if (!config("oauth_providers.enabled.$provider", false)) {
        abort(404);
    }
    return Socialite::driver($provider)->redirect();
})->name('oauth.redirect');

Route::get('/oauth/{provider}/callback', function ($provider) {
    $oauthUser = Socialite::driver($provider)->stateless()->user();

    // Handle the user (create or link account)
    // For example:
    $user = User::firstOrCreate(
        ['email' => $oauthUser->getEmail()],
        [
            'name' => $oauthUser->getName(),
            'email_verified_at' => now(),
        ]
    );

    // Attach social account
    $user->socialAccounts()->updateOrCreate(
        ['provider_name' => $provider],
        ['provider_id' => $oauthUser->getId()]
    );

    // Log in the user
    Auth::login($user);

    return redirect()->intended('/dashboard');
})->name('oauth.callback');