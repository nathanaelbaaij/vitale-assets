{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Error: 403')

@section('content_header')
    <h1>Error: 403</h1>
    {{ Breadcrumbs::render('403') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="error-page">
        <h2 class="headline text-yellow">403</h2>
        <div class="error-content">
            <h3><i class="fa fa-warning text-yellow"></i> Toegang geweigerd</h3>
            <p>U heeft geen toestemming om deze pagina te bezoeken. Voor meer informatie kan je contact opnemen met de administrator.
                Klik op de onderstaande knop om terug te gaan naar het dashboard.</p>
            <a href="{{ route('dashboard') }}" class="btn btn-primary">Ga naar dashboard</a>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop