@component('mail::message')

# Hey, {{ $full_name }} !!

Your account verification code for password recovery is given below

<div align="center">
     <b> {{ $code }} </b>
</div

@component('mail::button', ['url' => url('/api')])
Set a new password now
@endcomponent

Thank you. <br>
@endcomponent
