@extends('adminlte::page')

@section('title', 'Breslocaties')

@section('content_header')
    <h1>Breslocaties</h1>
    {{ Breadcrumbs::render('breaches') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle breslocaties</h3>
                    @can('breach-create')
                        <a href="{{route('breaches.create')}}" class="btn btn-success pull-right">Breslocatie toevoegen</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="breachlocations-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                        <tr role="row">
                                            <th>Code</th>
                                            <th>Naam</th>
                                            <th>Langenaam</th>
                                            <th>X-coördinaat</th>
                                            <th>Y-coördinaat</th>
                                            <th>Dijkring</th>
                                            <th>VNK2</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if(count($breaches) > 0)
                                            @foreach($breaches as $breach)
                                                <tr role="row">
                                                    <td>{{ $breach->code }}</td>
                                                    <td>
                                                        <a href="{{ route('breaches.show', $breach->id)}}">{{$breach->name }}</a>
                                                    </td>
                                                    <td>{{$breach->longname}}</td>
                                                    <td>{{$breach->xcoord}}</td>
                                                    <td>{{$breach->ycoord}}</td>
                                                    <td>{{$breach->dykering}}</td>
                                                    <td>{{$breach->vnk2}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>

                                    <tfoot>
                                        <th colspan="1">Code</th>
                                        <th colspan="1">Naam</th>
                                        <th colspan="1">Langenaam</th>
                                        <th colspan="1">X-coördinaat</th>
                                        <th colspan="1">Y-coördinaat</th>
                                        <th colspan="1">Dijkring</th>
                                        <th colspan="1">VNK2</th>
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