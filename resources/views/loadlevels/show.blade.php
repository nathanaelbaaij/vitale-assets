{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Belastingniveau')

@section('content_header')
    <h1>{{ $loadlevel->name }}</h1>
    {{ Breadcrumbs::render('loadlevel', $loadlevel) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Belastingniveau</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>

                        <tr>
                            <th style="width: 25%;">Id</th>
                            <td>{{ $loadlevel->id }}</td>
                        </tr>
                        <tr>
                            <th>Code</th>
                            <td>{{ $loadlevel->code }}</td>
                        </tr>
                        <tr>
                            <th>Naam</th>
                            <td>{{ $loadlevel->name }}</td>
                        </tr>
                        <tr>
                            <th>Beschrijving</th>
                            <td>
                                @if($loadlevel->description)
                                    {{ $loadlevel->description }}
                                @else
                                    {{ '(geen beschrijving)' }}
                                @endif
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">
                        @can('loadlevel-edit')
                            <a href="{{ route('loadlevels.edit', ['id' => $loadlevel->id]) }}" type="button"
                               class="btn btn-warning">
                                Wijzigen
                            </a>
                        @endcan
                        @can('loadlevel-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                        @endcan
                    </div>

                </div>

            </div>
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'belastingniveau', 'action' => route('loadlevels.destroy', $loadlevel->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop