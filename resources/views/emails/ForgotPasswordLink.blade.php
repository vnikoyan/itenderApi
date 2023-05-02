@component('mail::message')
{{ $data['title']}} 

@component('mail::button', ['url' => $data['url'] ])
Resent password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
