@component('mail::message', ['sender' => 'SpeedCartel'])
# Verify Your SpeedCartel Account

Hi {{ $name }},

Thanks for signing up for SpeedCartel! Please click the button below to verify your email address.

@component('mail::button', ['url' => $url, 'color' => 'red'])
Verify Email Address
@endcomponent

If the button above doesn't work, copy and paste the following URL into your browser:
{{ $url }}

If you did not create an account, no further action is required.

Regards,<br>
The SpeedCartel Team

@component('mail::subcopy')
This email was sent to {{ $email }}
@endcomponent
@endcomponent 