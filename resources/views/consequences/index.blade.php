@extends('adminlte::page')

@section('title', 'Consequenties')

@section('content_header')
    <h1>Consequenties</h1>
    {{ Breadcrumbs::render('consequences') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle consequenties</h3>
                    @can('consequence-create')
                        <a href="{{route('consequences.create')}}" class="btn btn-success pull-right">Consequentie toevoegen</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="consequences-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                    <tr role="row">
                                        <th>Id</th>
                                        <th>Beschrijving</th>
                                        <th>Acties</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if($consequences->count())
                                        @foreach($consequences as $consequence)
                                            <tr role="row">
                                                <td>{{$consequence->id}}</td>
                                                <td>
                                                    <a href="{{ route('consequences.show', $consequence->id)}}">{{$consequence->description}}</a>
                                                </td>
                                                <td>
                                                    @can('consequence-edit') <a href="{{route('consequences.edit', $consequence->id)}}" class="btn btn-warning btn-sm">Wijzigen</a> @endcan
                                                    @can('consequence-delete') <a href="{{route('consequences.delete', $consequence->id)}}" class="btn btn-danger btn-sm">Verwijderen</a> @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                    <tfoot>
                                    <th colspan="1">Id</th>
                                    <th colspan="1">Beschrijving</th>
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