{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Cascade')

@section('content_header')
    <h1>Cascade</h1>
    {{ Breadcrumbs::render('cascade', $cascade) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Cascade</h3>
                </div>

                <div class="box-body">
                    @include("cascades.partials.table")
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">
                        @can('cascade-edit')
                            <a href="{{ route('cascades.edit', ['id' => $cascade->id]) }}" type="button" class="btn btn-warning">
                                Wijzigen
                            </a>
                        @endcan
                        @can('cascade-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                        @endcan
                    </div>

                </div>

            </div>
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'cascade', 'action' => route('cascades.destroy', $cascade->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop