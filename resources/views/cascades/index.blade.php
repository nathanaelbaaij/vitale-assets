@extends('adminlte::page')

@section('title', 'Cascades')

@section('content_header')
    <h1>Cascades</h1>
    {{ Breadcrumbs::render('cascades') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle cascades</h3>
                    @can('cascade-create')
                        <a href="{{route('cascades.create')}}" class="btn btn-success pull-right">Cascade toevoegen</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="cascades-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                    <tr role="row">
                                        <th>Id</th>
                                        <th>Asset van</th>
                                        <th>Asset naar</th>
                                        <th>Consequentie</th>
                                        <th>Acties</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if($cascades->count())
                                        @foreach($cascades as $cascade)
                                            <tr role="row">
                                                <td>{{$cascade->id}}</td>
                                                <td>
                                                    <a href="{{ route('assets.show', $cascade->asset_id_from)}}">{{$cascade->assetFrom->id . ': ' . $cascade->assetFrom->name}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('assets.show', $cascade->asset_id_to)}}">{{$cascade->assetTo->id . ': ' . $cascade->assetTo->name}}</a>
                                                </td>
                                                <td>
                                                    <a href="{{ route('consequences.show', $cascade->consequence->id)}}">{{$cascade->consequence->description}}</a>
                                                </td>
                                                <td>
                                                    @can('cascade-list') <a href="{{route('cascades.show', $cascade->id)}}" class="btn btn-primary btn-sm">Bekijken</a> @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                    <tfoot>
                                        <th colspan="1">Id</th>
                                        <th colspan="1">Asset van</th>
                                        <th colspan="1">Asset naar</th>
                                        <th colspan="1">Consequentie</th>
                                        <th colspan="1">Acties</th>
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