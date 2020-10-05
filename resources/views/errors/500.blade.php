@extends('adminlte::master')

@section('title', 'Error: 500')

@section('body')
    <div class="wrapper">
        <div class="container">
            <section class="content">
                <h1>500 error</h1>
                <p>Er ging iets mis, ga terug naar <a href="{{ url('/') }}">home</a>.</p>
            </section>
        </div>
    </div>
@stop
