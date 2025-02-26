@component('mail::message')
# Introduction

Please Click this Button to reset your password

#ignore if you did not request for reset password

@component('mail::button', ['url' => config('app.frontend_url'). '/confirm_password?token='.$token])
Change password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
