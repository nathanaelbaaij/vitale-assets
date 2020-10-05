@extends('adminlte::master')

@section('title', 'Error: 404')

@section('body')
    <div class="wrapper">
        <div class="container">
            <section class="content">
                <h1>404 error</h1>
                <p>Pagina niet gevonden ga terug naar het <a href="{{ route('dashboard') }}">dashboard</a>.</p>
            </section>
        </div>
    </div>
@stop
