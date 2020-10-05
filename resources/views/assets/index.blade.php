@extends('adminlte::page')

@section('title', 'Assets')

@section('content_header')
    <h1>Assets</h1>
    {{ Breadcrumbs::render('assets') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle assets</h3>
                    @can('asset-create')
                        <a href="{{route('assets.create')}}" class="btn btn-success pull-right">Asset toevoegen</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="assets-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                    <tr role="row">
                                        <th>Naam</th>
                                        <th>Categorie</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if(count($assets) > 0)
                                        @foreach($assets as $asset)
                                            <tr role="row">
                                                <td>
                                                    <a href="{{ route('assets.show', $asset->id) }}">{{ $asset->name }}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('categories.show', $asset->category->id) }}">{{ $asset->category->name }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                    <tfoot>
                                    <tr role="row">
                                        <th colspan="1">Naam</th>
                                        <th colspan="1">Categorie</th>
                                    </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script src="/js/customDataTables.js"></script>
@stop