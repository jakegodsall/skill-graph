<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'admin_view' => false,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Get the original email address
        $originalEmail = $user->email;

        // Update the user's name
        $user->fill(['name' => $request->validated('name')]);

        // Check if email is being updated
        if ($request->validated('email') !== $user->email) {
            // Store the new email in a pending_email field
            $user->pending_email = $request->validated('email');

            // Send verification email to the new email address
            $user->sendEmailVerificationNotificationToNewEmail($user->pending_email);
            // Send notification email to the old address
            $user->sendEmailChangeNotificationToOriginalEmail($user->pending_email);

            $user->save();

            return Redirect::route('profile.edit')->with([
                'status' => 'profile-updated',
                'email-sent' => 'An email has been sent to confirm your new email address.',
            ]);
        }

        // Save the user's profile
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
