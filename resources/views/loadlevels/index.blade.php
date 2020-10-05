@extends('adminlte::page')

@section('title', 'Belastingniveaus')

@section('content_header')
    <h1>Belastingniveaus</h1>
    {{ Breadcrumbs::render('loadlevels') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle belastingniveaus</h3>
                    @can('loadlevel-create')
                        <a href="{{route('loadlevels.create')}}" class="btn btn-success pull-right">Belastingniveau toevoegen</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="loadlevels-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                        <tr role="row">
                                            <th>Code</th>
                                            <th>Naam</th>
                                            <th>Beschrijving</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                    @if($loadLevels->count())
                                        @foreach($loadLevels as $loadLevel)
                                            <tr role="row">
                                                <td>{{ $loadLevel->code }}</td>
                                                <td>
                                                    <a href="{{ route('loadlevels.show', $loadLevel->id)}}">{{$loadLevel->name }}</a>
                                                </td>
                                                <td>{{ $loadLevel->description }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                    <tfoot>
                                        <th colspan="1">Code</th>
                                        <th colspan="1">Naam</th>
                                        <th colspan="1">Beschrijving</th>
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