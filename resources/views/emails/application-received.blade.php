<x-mail::message>
# {{ __('site.careers.email.heading') }}

**{{ __('fields.name') }}:** {{ $application->name }}
**{{ __('fields.email') }}:** {{ $application->email }}
**{{ __('fields.phone') }}:** {{ $application->phone ?: '—' }}
**{{ __('fields.area') }}:** {{ $areaLabel }}

{{ __('site.careers.email.attachment_note') }}

<x-mail::button :url="'mailto:' . $application->email">
{{ __('site.careers.email.reply') }}
</x-mail::button>

{{ config('client.legal_name') }}
</x-mail::message>
