@component('mail::message')

# Hey, {{ $full_name }} !!

Your account verification code for password recovery is given below

<div align="center">
     <b> {{ $code }} </b>
</div

Thank you. <br>
@endcomponent
