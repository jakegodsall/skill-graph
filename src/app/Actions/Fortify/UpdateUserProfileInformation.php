<?php

namespace App\Actions\Fortify;

use App\Models\Auth\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, string>  $input
     */
    public function update(User $user, array $input): void
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ])->validateWithBag('updateProfileInformation');

        // Update name directly
        $user->forceFill(['name' => $input['name']])->save();

        // If email change, set the pending email in the db
        if ($input['email'] !== $user->email) {
            $this->storePendingEmail($user, $input['email']);
        }
    }

    protected function storePendingEmail(User $user, string $newEmail): void
    {
        $user->forceFill([
           'pending_email' => $newEmail, 
        ])->save();

        // Send verification email to the new address
        if ($user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotificationToNewEmail($newEmail);
        }
    }

}
