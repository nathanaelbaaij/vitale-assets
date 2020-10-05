@extends('adminlte::master')

@section('title', 'Uitnodiging ongeldig')

@section('adminlte_css')
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/css/auth.css') }}">
    @yield('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('body_class', 'login-page')

@section('body')
    <div class="login-box">
        <div class="login-logo">
            <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</a>
        </div>
        <div class="login-box-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <h3 class="box-title">Uitnodiging ongeldig</h3>
            <p>Je uitnodiging is verlopen of al geactiveerd. Neem contact op met de administrator voor meer informatie.</p>
        </div>
    </div>
@stop

@section('adminlte_js')
    @yield('js')
@stop
