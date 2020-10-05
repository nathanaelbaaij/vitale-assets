@extends('adminlte::page')

@section('title', 'Breslocatie wijzigen')

@section('content_header')
    <h1>Breslocaties wijzigen</h1>
    {{ Breadcrumbs::render('breach', $breach) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            @include('vendor.adminlte.partials.errors')

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Wijzigen van breslocatie "{{$breach->name}}"</h3>
                    @include('forms.required')
                </div>

                <form action="{{route('breaches.update', $breach->id)}}" method="post">

                    @method('PUT')
                    @csrf

                    <div class="box-body">

                        <div class="form-group">
                            <label class="required" for="breachLocationCode">Code</label>
                            <input type="text" class="form-control" id="breachLocationCode" placeholder="Code"
                                   name="code" value="{{ $breach->code }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="required" for="breachLocationName">Naam</label>
                            <input type="text" class="form-control" id="breachLocationName" placeholder="Naam"
                                   name="name" value="{{ $breach->name }}" required>
                        </div>

                        <div class="form-group">
                            <label class="required" for="breachLocationLongname">Langenaam</label>
                            <input type="text" class="form-control" id="breachLocationLongname" placeholder="Langenaam"
                                   name="longname" value="{{ $breach->longname }}" required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationXcoord">X-coördinaat</label>
                                    <input type="number" class="form-control" id="breachLocationXcoord"
                                           placeholder="X-coördinaat" name="x_coordinate" value="{{ $breach->xcoord }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationYcoord">Y-coördinaat</label>
                                    <input type="number" class="form-control" id="breachLocationYcoord"
                                           placeholder="Y-coördinaat" name="y_coordinate" value="{{ $breach->ycoord }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationDykring">Dijkring</label>
                                    <input type="number" class="form-control" id="breachLocationDykring"
                                           placeholder="Dijkring" name="dykering" value="{{ $breach->dykering }}" required>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="required" for="breachLocationVNK2">VNK2</label>
                                    <input type="number" class="form-control" id="breachLocationVNK2" placeholder="VNK2"
                                           name="vnk2" value="{{ $breach->vnk2 }}" required>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="{{URL::previous()}}" class="btn btn-default btn-detail open-modal">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Wijzigen</button>
                    </div>

                </form>

            </div>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop