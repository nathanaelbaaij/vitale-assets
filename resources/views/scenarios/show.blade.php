{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Scenario')

@section('content_header')
    <h1>{{ $scenario->name }}</h1>
    {{ Breadcrumbs::render('scenario', $scenario) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Scenario</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th style="width: 25%;">Code</th>
                                <td>{{ $scenario->name }}</td>
                            </tr>
                            <tr>
                                <th>Aangemaakt</th>
                                <td>{{ $scenario->created_at->format('H:i - d F Y') }}</td>
                            </tr>
                            <tr>
                                <th>Bijgewerkt</th>
                                <td>{{ $scenario->created_at->diffForHumans() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">

                        @if($scenario->id == $currentUserScenario)
                            <td><a href="#" class="btn btn-success disabled"><i class="fa fa-download"></i> Huidige scenario</a></td>
                        @else
                            <td><a href="{{ route('scenario.switch', $scenario->id) }}" class="btn btn-primary"><i class="fa fa-download"></i> Laad scenario in</a></td>
                        @endif

                        @can('scenario-edit')
                            <a href="{{ route('scenarios.edit', ['id' => $scenario->id]) }}" type="button"
                               class="btn btn-warning">
                                Wijzigen
                            </a>
                        @endcan
                        @can('scenario-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">
                                Verwijderen
                            </button>
                        @endcan
                    </div>

                </div>

            </div>
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'scenario', 'action' => route('scenarios.destroy', $scenario->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop