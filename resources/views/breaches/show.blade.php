@extends('adminlte::page')

@section('title', 'Breslocaties')

@section('content_header')
    <h1>{{ $breach->name }}</h1>
    {{ Breadcrumbs::render('breach', $breach) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Breslocatie</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 25%;">Id</th>
                            <td>{{ $breach->id }}</td>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <td>{{ $breach->code }}</td>
                        </tr>
                        <tr>
                            <th>Naam</th>
                            <td>{{ $breach->name }}</td>
                        </tr>
                        <tr>
                            <th>Langenaam</th>
                            <td>{{ $breach->longname }}</td>
                        </tr>
                        <tr>
                            <th>X-coördinaat</th>
                            <td>{{ $breach->xcoord }}</td>
                        </tr>
                        <tr>
                            <th>Y-coördinaat</th>
                            <td>{{ $breach->ycoord }}</td>
                        </tr>
                        <tr>
                            <th>Dijkring</th>
                            <td>{{ $breach->dykering }}</td>
                        </tr>
                        <tr>
                            <th>VNK2</th>
                            <td>{{ $breach->vnk2 }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">
                        @can('breach-edit')
                            <a href="{{ route('breaches.edit', ['id' => $breach->id]) }}" type="button" class="btn btn-warning">
                                Wijzigingen
                            </a>
                        @endcan
                        @can('breach-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                        @endcan
                    </div>

                </div>

            </div>
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'breslocatie', 'action' => route('breaches.destroy', $breach->id)])

@stop



@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop