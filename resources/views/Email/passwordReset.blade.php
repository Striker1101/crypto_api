@component('mail::message')
# Introduction

Please Click this Button to reset your password

#ignore if you did not request for reset password

@component('mail::button', ['url' => 'http://localhost:5501/dashboard/user/change-password-confirm.html?token='.$token])
Change password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
