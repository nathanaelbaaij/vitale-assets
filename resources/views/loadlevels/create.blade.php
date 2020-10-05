@extends('adminlte::page')

@section('title', 'Belastingniveau aanmaken')

@section('content_header')
    <h1>Belastingniveau aanmaken</h1>
    {{ Breadcrumbs::render('loadlevelCreate') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Toevoegen
                    </h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('loadlevels.store') }}">

                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required" for="loadLevelCode">Code</label>
                                    <input type="text" class="form-control" id="loadLevelCode" placeholder="Code" name="code" value="{{ old('code') }}" required autofocus>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required" for="loadLevelName">Naam</label>
                                    <input type="text" class="form-control" id="loadLevelName" placeholder="Naam" name="name" value="{{ old('name') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="loadLevelDescription">Beschrijving</label>
                            <textarea name="description" id="loadLevelDescription" class="form-control" rows="3"
                                      placeholder="Toelichting van het belastingniveau...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Belastingniveau toevoegen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop