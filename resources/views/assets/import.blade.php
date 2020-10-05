@extends('adminlte::page')

@section('title', 'Assets importeren')

@section('content_header')
    <h1>Assets importeren</h1>
    {{ Breadcrumbs::render('assetImport') }}
@stop

@section('content')

    <div class="row">
        <!-- foutmelding -->
        @include('vendor.adminlte.partials.session')
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Uploaden
                    </h3>
                </div>

                <form action="{{ route('assets.import.store') }}" enctype="multipart/form-data" method="post" id="form" class="create">
                    @csrf
                    <div class="box-body">
                        <p>Upload hier uw assets bestand.</p>
                        <input type="file" name="geojson_file">
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success pull-right">Bestand uploaden</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="{{ asset('js/assetCreate.js') }}"></script>
    <script src="{{ asset('js/thresholdCheck.js') }}"></script>
    <script src="{{ asset('js/properties.js') }}"></script>
@stop