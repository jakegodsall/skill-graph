<x-mail::message>
# Email Address Change Request

Hello {{ $user->name }},

We wanted to inform you that the email address associated with your account has been requested to change from **{{ $user->email }}** to **{{ $newEmail }}**.

If you did not request this change, please contact our support team immediately at [support@example.com](mailto:support@example.com).

If this change was intentional, no further action is needed.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
