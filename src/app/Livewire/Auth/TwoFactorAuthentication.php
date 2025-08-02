<?php

namespace App\Livewire\Auth;

use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Livewire\Component;

class TwoFactorAuthentication extends Component
{
    public User $user;
    public bool $twoFactorEnabled;
    public bool $twoFactorConfirmed = false;
    public bool $showingQrCode = false;
    public bool $showingRecoveryCodes = false;
    public ?bool $recoveryCodesConfirmed = false;
    public bool $confirmationChecked = false;
    public string $code;
    public string $password = '';

    public function mount()
    {
        $this->user = Auth::user();

        $this->twoFactorEnabled = !is_null($this->user->two_factor_secret);
        $this->twoFactorConfirmed = !is_null($this->user->two_factor_confirmed_at);
        $this->recoveryCodesConfirmed = !is_null($this->user->two_factor_recovery_codes_confirmed);

        $this->showingQrCode = $this->twoFactorEnabled && is_null($this->user->two_factor_confirmed_at);
        $this->showingRecoveryCodes = $this->twoFactorEnabled && $this->twoFactorConfirmed && !$this->recoveryCodesConfirmed;
    }

    public function enableTwoFactorAuthentication()
    {

        $this->user->forceFill([
            'two_factor_secret' => encrypt(app(TwoFactorAuthenticationProvider::class)->generateSecretKey()),
        ])->save();

        $this->twoFactorEnabled = true;
        $this->showingQrCode = true;
    }

    public function confirmTwoFactorAuthentication()
    {
        $provider = app(TwoFactorAuthenticationProvider::class);
        $twoFactorSecret = decrypt($this->user->two_factor_secret);

        // Validate the TOTP
        if (!$provider->verify($twoFactorSecret, $this->code)) {
            $this->addError('code', __('The provided two-factor authentication code was invalid.'));
            return;
        }

        // Confirm 2FA
        $this->user->forceFill(['two_factor_confirmed_at' => now()])->save();
        $this->twoFactorConfirmed = true;
        
        // Remove the QR code from the view
        $this->showingQrCode = false;
        // Start the recovery code flow
        $this->handleRecoveryCodes();
    }

    public function handleRecoveryCodes(): void
    {
        // Generate the recovery codes for the user
        $this->user->setRecoveryCodes();
        // Show the recovery codes in the view
        $this->showingRecoveryCodes = true;
    }

    public function downloadAllRecoveryCodes()
    {
        $recoveryCodes = $this->user->recoveryCodes();
        $fileContent = implode("\n", $recoveryCodes);
        $headers = [
            'Content-Type' => 'text/plain',
            'Content-Disposition' => 'attachment; filename="recovery-codes.txt"',
        ];
        return response()->streamDownload(function () use ($fileContent) {
            echo $fileContent;
        }, 'recovery-codes.txt', $headers);
    }

    public function proceedAfterRecoveryCodes()
    {
        if (!$this->confirmationChecked) {
            $this->addError('confirmationChecked', __('You must confirm that you have stored your recovery codes safely.'));
            return;
        }

        $this->user->forceFill([
            'two_factor_recovery_codes_confirmed' => true,
        ])->save();

        // Update the component state
        $this->recoveryCodesConfirmed = true;
        $this->showingRecoveryCodes = false;

        session()->flash('message', __('Recovery codes have been stored. Two-factor authentication setup is complete.'));
    }

    public function disableTwoFactorAuthentication()
    {
        if ($this->password !== '' && !Hash::check($this->password, $this->user->password)) {
            $this->addError('password', __('The provided password is incorrect.'));
            return;
        }
        

        // Disable 2FA
        $this->user->forceFill([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_recovery_codes_confirmed' => null,
        ])->save();

        $this->twoFactorEnabled = false;
        $this->twoFactorConfirmed = false;
        $this->showingQrCode = false;
        $this->showingRecoveryCodes = false;

        $this->dispatch('disable-two-factor-completed');
        session()->flash('message', __('Two-factor authentication has been disabled.'));
    }

    public function render()
    {
        return view('livewire.auth.two-factor-authentication', [
            '$this->user' => $this->user,
            'recoveryCodes' => $this->showingRecoveryCodes
                ? $this->user->recoveryCodes()
                : [],
        ]);
    }
}
