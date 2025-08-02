<x-mail::message>
# Email Address Changed Successfully

Hello {{ $user->name }},

We wanted to let you know that your email address has been updated successfully.

If you did not request this change, please contact our support team immediately at [support@example.com](mailto:support@example.com).

Thanks for keeping your account secure.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
