@extends('adminlte::page')

@section('title', 'Scenarios')

@section('content_header')
    <h1>Scenarios</h1>
    {{ Breadcrumbs::render('scenarios') }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Overzicht van jouw scenario's</h3>
                    @can('scenario-create')
                        <a href="{{route('scenarios.create')}}" class="btn btn-success pull-right">Nieuw scenario</a>
                    @endcan
                </div>
                <div class="box-body">
                    <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                        <div class="row">
                            <div class="col-md-12">

                                <table id="scenarios-table" class="table table-bordered table-hover dataTable">
                                    <thead>
                                    <tr role="row">
                                        <th>Naam</th>
                                        <th>In gebruik door</th>
                                        <th>Aangemaakt</th>
                                        <th>Bijgewerkt</th>
                                        <th>Inladen</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @if($scenarios->count())
                                        @foreach($scenarios as $scenario)
                                            <tr role="row">
                                                <td>
                                                    <a href="{{ route('scenarios.show', $scenario->id) }}">{{ $scenario->name }}</a>
                                                </td>
                                                <td>
                                                   @foreach($scenario->users as $user)

                                                       @if (Auth::user()->can('user-list'))
                                                            <a href="{{ route('users.show', $user->id) }}"><span class="label label-primary">{{ $user->name }}</span></a>
                                                       @else
                                                            <span class="label label-primary">{{ $user->name }}</span>&nbsp;
                                                       @endif

                                                   @endforeach
                                                </td>
                                                <td>{{ $scenario->created_at->format('H:i - d F Y') }}</td>
                                                <td>{{ $scenario->updated_at->diffForHumans() }}</td>

                                                @if($scenario->id == $currentUserScenario)
                                                    <td><a href="#" class="btn btn-success disabled"><i class="fa fa-download"></i> Huidige scenario</a></td>
                                                @else
                                                    <td><a href="{{ route('scenario.switch', $scenario->id) }}" class="btn btn-primary"><i class="fa fa-download"></i> Laad scenario in</a></td>
                                                @endif

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>

                                    <tfoot>
                                    <tr>
                                        <th>Naam</th>
                                        <th>In gebruik door</th>
                                        <th>Aangemaakt</th>
                                        <th>Bijgewerkt</th>
                                        <th>Inladen</th>
                                    </tr>
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