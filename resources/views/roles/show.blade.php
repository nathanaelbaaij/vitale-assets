{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Rol')

@section('content_header')
    <h1>Rol</h1>
    {{ Breadcrumbs::render('role', $role) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-8">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Role</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th width="25%">Naam</th>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <th>Permissies</th>
                            <td>
                                @foreach($role->permissions()->pluck('name') as $roleName)
                                    <div class="col-md-2">
                                        <span class="label label-primary">{{ $roleName }}</span>
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">
                        @can('role-edit')
                            <a href="{{ route('roles.edit', ['id' => $role->id]) }}" type="button"
                               class="btn btn-warning">
                                Wijzigingen
                            </a>
                        @endcan
                        @can('role-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                        @endcan
                    </div>

                </div>

            </div>
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'rol', 'action' => route('roles.destroy', $role->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop