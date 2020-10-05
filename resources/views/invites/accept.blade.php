@extends('adminlte::master')

@section('title', 'Account aanmaken')

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
            <h3 class="box-title">Account aanmaken</h3>
            <p>De administrator heeft u uitgenodigd om mee te kunnen delen aan  {{ config('app.name') }}. Vul het onderstaande formulier in om een account aan te maken.</p>
            <form action="{{ route('invites.store') }}" method="post">

                {!! csrf_field() !!}

                <input type="hidden" name="invite_token" value="{{ $invite->token }}">

                <div class="form-group">
                    <label for="email">E-mailadres</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $invite->email }}" disabled>
                </div>

                <div class="form-group has-feedback {{ $errors->has('name') ? 'has-error' : '' }}">
                    <label class="required" for="name">Naam</label>
                    <input type="text" name="name" class="form-control" value="{{ $invite->name ?? old('name') }}" id="name" autocomplete="username" required>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label class="required" for="password">Wachtwoord</label>
                    <input type="password" required="required" class="form-control" id="password" name="password" autocomplete="new-password">

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <label class="required" for="password_confirmation">Wachtwoord bevestiging</label>
                    <input type="password" required="required" class="form-control" id="password_confirmation" name="password_confirmation" autocomplete="new-password">

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary btn-block btn-flat">Account aanmaken</button>
            </form>
        </div>
    </div>
@stop

@section('adminlte_js')
    @yield('js')
@stop
