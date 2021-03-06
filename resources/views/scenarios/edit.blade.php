@extends('adminlte::page')

@section('title', 'Scenario wijzigen')

@section('content_header')
    <h1>Scenario wijzigen</h1>
    {{ Breadcrumbs::render('scenario', $scenario) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Aanpassen</h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('scenarios.update', $scenario->id) }}">

                    @method('PUT')
                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="scenarioName">Naam</label>
                            <input type="text" class="form-control" id="scenarioName" placeholder="Scenario naam" name="name" value="{{ $scenario->name }}" required autofocus>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Scenario wijzigen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop