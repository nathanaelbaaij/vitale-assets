@extends('adminlte::page')

@section('title', 'Asset aanpassen')

@section('content_header')
    <h1>Asset aanpassen</h1>
    {{ Breadcrumbs::render('asset', $asset) }}
@stop

@section('content')

    <div class="row">
        <div class="col-sm-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">Invoeren</h3>
                    @include('forms.required')
                </div>

                @includeIf("assets.partials.form_edit")
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
    <script src="{{ asset('js/assetEdit.js') }}"></script>
    <script src="{{ asset('js/deleteDom.js') }}"></script>
    <script src="{{ asset('js/thresholdCheck.js') }}"></script>
    <script src="{{ asset('js/properties.js') }}"></script>
@stop