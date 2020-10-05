@extends('adminlte::page')

@section('title', 'Uitnodigingen')

@section('content_header')
    <h1>Uitnodigingen</h1>
    {{ Breadcrumbs::render('invites') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van alle uitnodigingen</h3>
                    @can('breach-create')
                        <a href="{{route('invites.create')}}" class="btn btn-success pull-right">Gebruiker uitnodigingen</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="invites-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                    <tr role="row">
                                        <th>Naam</th>
                                        <th>E-mailadres</th>
                                        <th>Token</th>
                                        <th>Acties</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if(count($invites) > 0)
                                        @foreach($invites as $invite)
                                            <tr role="row">
                                                <td>{{ $invite->name }}</td>
                                                <td>{{ $invite->email }}</td>
                                                <td>{{ $invite->token }}</td>
                                                <td>
                                                    @can('invite-delete')
                                                        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                                                    @endcan
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                    <tfoot>
                                    <th colspan="1">Naam</th>
                                    <th colspan="1">E-mailadres</th>
                                    <th colspan="1">Token</th>
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

    @if(count($invites) > 0)
        @foreach($invites as $invite)
            @includeIf('modals.delete', ['name' => 'uitnodiging', 'action' => route('invites.destroy', $invite->id)])
        @endforeach
    @endif

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script src="/js/customDataTables.js"></script>
@stop