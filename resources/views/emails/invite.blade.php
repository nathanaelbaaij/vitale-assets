@component('mail::message')
# Beste {{ $invite->name }},

Je bent uitgenogdigd om deel te nemen aan Vitale Assets.

Er moet alleen nog een wachtwoord worden ingesteld.

@component('mail::button', ['url' => route('invites.accept', $invite->token)])
    Wachtwoord instellen
@endcomponent

Je kunt na bevestiging inloggen met het volgende e-mailadres: <a href="mailto:{{ $invite->email }}">{{ $invite->email }}</a>

Wil je geen toegang hebben tot {{ config('app.name') }} of weet je niet waar dit over gaat? Dan verzoeken wij je dit mailtje te negeren en niet op de activatie-link te klikken. Onze excuses voor het ongemak.

Met vriendelijke groet,<br>
{{ config('app.name') }}

@component('mail::subcopy')
Als je moeite hebt om op de knop "Wachtwoord instellen" te klikken, kopieer en plak de onderstaande link in je web browser: <a href="{{ route('invites.accept', $invite->token) }}">{{ route('invites.accept', $invite->token) }}</a>
@endcomponent
@endcomponent