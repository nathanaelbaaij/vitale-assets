@extends('adminlte::page')

@section('title', 'Asset categorie overzicht')

    @section('content_header')
        <h1>Asset categorieën</h1>
    {{ Breadcrumbs::render('categories') }}
@stop

@section('content')

    <!-- session -->
    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle asset hoofdcategorieën</h3>
                    @can('asset-create')
                        <a href="{{ route('assets.import') }}" class="btn btn-success pull-right">Assets importeren</a>
                    @endcan
                </div>

                @if(count($categories))

                    <div class="box-body">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                            @include('categories.partials.table-index')

                        </div>
                    </div>

                @else
                    <div class="box-body">
                        Geen asset categorieën beschikbaar.
                    </div>
                @endif

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop