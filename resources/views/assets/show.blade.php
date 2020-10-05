{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Asset')

@section('content_header')
    <h1>Asset: {{ $asset->name }}</h1>
    {{ Breadcrumbs::render('asset', $asset) }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <!-- asset information -->
    <div class="row">
        <div class="col-md-4">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Informatie over {{ $asset->name }}</h3>
                </div>

                <div class="box-body">
                    @include("assets.partials.table")
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                    <div class="pull-right">
                        @can('asset-edit')
                            <a href='{{ route('assets.edit', ['id' => $asset->id]) }}' class='btn btn-warning'>Wijzigen</a>
                        @endcan
                        @can('asset-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
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
    <!-- asset information -->

    <!-- water problemen en comments -->
    <div class="row">
        @include("assets.partials.waterproblemen")
        @include("assets.partials.comments")
    </div>
    <!-- /water problemen en comments -->

    <!-- logboek -->
    @include("assets.partials.logbook")
    <!-- /logboek -->

    @includeIf('modals.delete', ['name' => 'asset', 'action' => route('assets.destroy', $asset->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="https://openlayers.org/en/v4.6.5/css/ol.css" type="text/css">
    <style>
        .map {
            height: 426px;
            width: 100%;
            position: relative;
        }

        .box_shadow {
            margin: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .comments {
            max-height: 238px;
            overflow-y: auto;
        }

        .comment-image {
            width: 100%;
            padding: 1em 0 1em 0;
        }

    </style>
@stop

@section('js')
    <script>
        //define asset information for js files
        window.assetId = '{{ $asset->id }}';
        window.assetName = '{{ $asset->name }}';
        window.assetGeometryJSON = {!! $asset->geometry !!};
    </script>
    <script src="https://openlayers.org/en/v4.6.5/build/ol.js" type="text/javascript"></script>
    <script src="{{ asset('js/assetShow.js') }}"></script>
    <script src="{{ asset('js/assetBreachlocationsTable.js') }}"></script>
@stop
