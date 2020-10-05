@extends('adminlte::page')

@section('title', 'Uitnodiging aanmaken')

@section('content_header')
    <h1>Uitnodiging aanmaken</h1>
    {{ Breadcrumbs::render('inviteCreate') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Uitnodiging
                    </h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('process') }}">

                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="name">Gebruikersnaam</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="required" for="email">E-mailadres</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Gebruiker uitnodigen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop