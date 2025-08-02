<x-mail::message>
# Verify Your New Email

Hello {{ $user->name }},

You recently requested to change your email address for your account. Please verify your new email address by clicking the button below:

<x-mail::button :url="$verificationUrl">
Verify Email
</x-mail::button>

If you did not request this change, please contact our support team immediately at [support@example.com](mailto:support@example.com).

Thank you for keeping your account secure.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
