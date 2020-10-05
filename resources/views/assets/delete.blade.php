{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Asset verwijderen')

@section('content_header')
    <h1>Asset verwijderen</h1>
    {{ Breadcrumbs::render('asset', $asset) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-4">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Weet u zeker om de volgende asset te verwijderen?</h3>
                </div>

                <div class="box-body">
                    @include("assets.partials.table")
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                    <div class="pull-right">
                        <form action="{{ route('assets.destroy', $asset->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <input type="hidden" name="url" value="{{ url()->previous() }}"/>
                            <button class="btn btn-danger" type="submit">
                                Definitief verwijderen
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
        <div class="col-md-5">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Kaart</h3>
                </div>
                <div class="box-body">
                    <div id="map" class="map">
                        <div id="popup"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Waterdieptes</h3>
                </div>
                <div class="box-body">

                    <div class="nav-tabs-custom">

                        <ul class="nav nav-tabs">
                            @foreach($loadLevels as $count => $loadLevel)
                                <li @if($count == 1) class="active" @endif>
                                    <a href="#loadlevel-{{ $loadLevel->id }}" data-toggle="tab"
                                       data-loadlevel="{{ $loadLevel->id }}" data-asset="{{ $asset->id }}"
                                       aria-expanded="false">
                                        {{ $loadLevel->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <div class="tab-content">

                            @foreach($loadLevels as $count => $loadLevel)
                                <div @if($count == 1) class="tab-pane active" @else class="tab-pane"
                                     @endif id="loadlevel-{{ $loadLevel->id }}">
                                    <table class="table table-bordered dataTable asset-breachlocation-waterdepth-table">
                                        <thead>
                                        <tr>
                                            <th>Breslocatie</th>
                                            <th>Waterdiepte</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tfoot>
                                        <tr>
                                            <th>Breslocatie</th>
                                            <th>Waterdiepte</th>
                                            <th>Status</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            @endforeach

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <style>
        .map {
            height: 389px;
            width: 100%;
            position: relative;
        }
    </style>
@stop

@section('js')
    <script>
        //define asset information for js files
        window.assetId = '{{ $asset->id }}';
        window.assetName = '{{ $asset->name }}';
        window.assetXCoord = '{{ $asset->x_coordinate }}';
        window.assetYCoord = '{{ $asset->y_coordinate }}';
    </script>
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="{{ asset('js/assetShow.js') }}"></script>
    <script src="{{ asset('js/assetBreachlocationsTable.js') }}"></script>
@stop