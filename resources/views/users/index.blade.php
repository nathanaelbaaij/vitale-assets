{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Gebruikers')

@section('content_header')
    <h1>Gebruikers</h1>
    {{ Breadcrumbs::render('users') }}
@stop

@section('content')

    <!-- session -->
    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-12">
            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Overzicht van alle gebruikers</h3>
                    @can('user-create')
                        <a href="{{ route('invites.create') }}" class="btn btn-success pull-right">Gebruiker toevoegen</a>
                    @endcan
                </div>

                @if(count($users))
                    <div class="box-body">
                        <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="example2" class="table table-bordered table-hover">

                                        <thead>
                                        <tr>
                                            <th>Naam</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Bedrijf</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        @foreach($users as $user)

                                            <tr>
                                                <td>
                                                    <a href="{{ route('users.show', ['user' => $user]) }}">{{ $user->name }}</a>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    @foreach ($user->roles as $role)
                                                        <span class="label label-primary">{{ $role->name }}</span>
                                                    @endforeach
                                                </td>
                                                <td>{{ $user->company }}</td>
                                            </tr>

                                        @endforeach

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                @else
                    <div class="box-body">
                        Geen gebruikers beschikbaar.
                    </div>
                @endif
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop