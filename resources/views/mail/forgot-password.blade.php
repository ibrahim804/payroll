@component('mail::message')

# Hey, {{ $full_name }} !!

Your account verification code for password recovery is given below

<div align="center">
     <b> {{ $code }} </b>
</div

@component('mail::button', ['url' => url('/api')]) <!-- url will be a get request of frontend, then the flow will call a post request of backend. -->
Set a new password now
@endcomponent

Thank you. <br>
@endcomponent
