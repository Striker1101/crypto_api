@component('mail::message')
# {{ $header }}

{{ $content }}



Thanks,<br>
from {{ config('app.name') }} team
contact {!! '<a href="mailto:support@' . config('app.name') . '.com">@support</a>' !!} for any support

## {{ $footer }}
@endcomponent


