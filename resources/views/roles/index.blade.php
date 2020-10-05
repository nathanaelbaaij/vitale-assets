{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Rollen')

@section('content_header')
    <h1>Rollen</h1>
    {{ Breadcrumbs::render('roles') }}
@stop

@section('content')

    <!-- session -->
    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Overzicht van alle rollen</h3>
                    @can('role-create')
                        <a href="{{ route('roles.create') }}" class="btn btn-success pull-right">Rol toevoegen</a>
                    @endcan
                </div>

                @if(count($roles))
                    <div class="box-body">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example2" class="table table-bordered table-hover">

                                        <thead>
                                        <tr>
                                            <th style="width: 25%">Naam</th>
                                            <th>Permissies</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($roles as $role)

                                            <tr>
                                                <td>
                                                    <a href="{{ route('roles.show', ['role' => $role]) }}">{{ $role->name }}</a>
                                                </td>
                                                <td>
                                                    @foreach($role->permissions()->pluck('name') as $roleName)
                                                        <div class="col-md-2">
                                                            <span class="label label-primary">{{ $roleName }}</span>
                                                        </div>
                                                    @endforeach
                                                </td>
                                            </tr>

                                        @endforeach

                                        </tbody>

                                        <tfoot>

                                        </tfoot>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="box-body">
                        Geen Rollen beschikbaar.
                    </div>
                @endif
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop