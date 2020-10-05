{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Consequentie')

@section('content_header')
    <h1>Consequentie</h1>
    {{ Breadcrumbs::render('consequence', $consequence) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Consequentie</h3>
                </div>

                <div class="box-body">
                    @include("consequences.partials.table")
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">
                        @can('consequence-edit')
                            <a href="{{ route('consequences.edit', ['id' => $consequence->id]) }}" type="button"
                               class="btn btn-warning">
                                Wijzigen
                            </a>
                        @endcan
                        @can('consequence-delete')
                            <a href="{{ route('consequences.delete', ['id' => $consequence->id]) }}" type="button"
                               class="btn btn-danger">
                                Verwijderen
                            </a>
                        @endcan
                    </div>

                </div>

            </div>
        </div>
        <div class="col-md-6">
            @include("consequences.partials.related")
        </div>
    </div>

@stop