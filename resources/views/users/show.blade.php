{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Gebruiker')

@section('content_header')
    <h1>{{ $user->name }}</h1>
    {{ Breadcrumbs::render('user', $user) }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-6">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Gebruiker</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 25%;">Naam</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Rol</th>
                            <td>
                                @if(!empty($user->getRoleNames()))
                                    @foreach($user->getRoleNames() as $role)
                                        <label class="label label-primary">{{ $role }}</label>
                                    @endforeach
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>E-mailadres</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Telefoon</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Bedrijf</th>
                            <td>{{ $user->company }}</td>
                        </tr>
                        <tr>
                            <th>Adres</th>
                            <td>{{ $user->adres }} {{ $user->house_number }} {{ $user->postal }} {{ $user->city }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                    <div class="pull-right">
                        @can('user-edit')
                            <a href="{{ route('users.edit', ['id' => $user->id]) }}" type="button" class="btn btn-warning">
                                Wijzigingen
                            </a>
                        @endcan
                        @can('user-delete')
                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                        @endcan
                    </div>
                </div>

            </div>
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'gebruiker', 'action' => route('users.destroy', $user->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop