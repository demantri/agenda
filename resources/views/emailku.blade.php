@component('mail::message')
# {{ $data['title'] }}

Saya sedang belajar mengirim email dengan Laravel.

@component('mail::button', ['url' => $data['url']])
Visit
@endcomponent

Terimakasi
{{ config('app.name') }}
@endcomponent