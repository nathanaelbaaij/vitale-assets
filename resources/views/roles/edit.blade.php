@extends('adminlte::page')

@section('title', 'Rol wijzigen')

@section('content_header')
    <h1>Rol wijzigen</h1>
    {{ Breadcrumbs::render('role', $role) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Rol wijzigen</h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('roles.update', $role->id) }}">

                    @method('PUT')
                    @csrf

                    <div class="box-body">

                        <div class="form-group">
                            <label for="name">Naam</label>
                            <input type="text" class="form-control" id="name" placeholder="Naam" name="name"
                                   value="{{ $role->name }}" disabled="disabled">
                        </div>

                        <hr>

                        <div class="form-group">
                            <h5 style="font-weight: bold;">Permissies</h5>

                            @foreach($permissions as $perm)
                                @php
                                    $per_found = null;
                                    if( isset($role) ) {
                                        $per_found = $role->hasPermissionTo($perm->name);
                                    }
                                    if( isset($user)) {
                                        $per_found = $user->hasDirectPermission($perm->name);
                                    }
                                @endphp

                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label class="{{ $perm->name }}">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ $per_found ? 'checked' : '' }} {{ $isAdmin ? 'disabled' : '' }}> {{ $perm->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Rol wijzigen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop