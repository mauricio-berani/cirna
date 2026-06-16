<x-mail::message>
# {{ __('site.contact.email.heading') }}

**{{ __('site.contact.fields.name') }}:** {{ $payload['name'] }}
**{{ __('site.contact.fields.email') }}:** {{ $payload['email'] }}
**{{ __('site.contact.fields.phone') }}:** {{ $payload['phone'] ?: '—' }}
**{{ __('site.contact.fields.sector') }}:** {{ $payload['sector_label'] }}

---

**{{ __('site.contact.fields.message') }}:**

{{ $payload['message'] }}

<x-mail::button :url="'mailto:' . $payload['email']">
{{ __('site.contact.email.reply') }}
</x-mail::button>

{{ config('client.legal_name') }}
</x-mail::message>
