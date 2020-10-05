@extends('adminlte::page')

@section('title', 'Breslocatie aanmaken')

@section('content_header')
    <h1>Breslocatie aanmaken</h1>
    {{ Breadcrumbs::render('breachCreate') }}
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

                <form method="POST" action="{{ route('breaches.store') }}">

                    @csrf

                    <div class="box-body">

                        <div class="form-group">
                            <label class="required" for="breachLocationCode">Code</label>
                            <input type="text" class="form-control" id="breachLocationCode" placeholder="Code" name="code" value="{{ old('code') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="required" for="breachLocationName">Naam</label>
                            <input type="text" class="form-control" id="breachLocationName" placeholder="Naam" name="name" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label class="required" for="breachLocationLongname">Langenaam</label>
                            <input type="text" class="form-control" id="breachLocationLongname" placeholder="Langenaam" name="longname" value="{{ old('longname') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationXcoord">X-coördinaat</label>
                                    <input type="number" class="form-control" id="breachLocationXcoord" placeholder="X-coördinaat" name="xcoord" value="{{ old('x_coordinate') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationYcoord">Y-coördinaat</label>
                                    <input type="number" class="form-control" id="breachLocationYcoord" placeholder="Y-coördinaat" name="ycoord" value="{{ old('y_coordinate') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationDykring">Dijkring</label>
                                    <input type="number" class="form-control" id="breachLocationDykring" placeholder="Dijkring" name="dykering" value="{{ old('dykering') }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationVNK2">VNK2</label>
                                    <input type="number" class="form-control" id="breachLocationVNK2" placeholder="VNK2" name="vnk2" value="{{ old('vnk2') }}" required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Breslocatie toevoegen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop
