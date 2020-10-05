@extends('adminlte::page')

@section('title', 'Role aanmaken')

@section('content_header')
    <h1>Role aanmaken</h1>
    {{ Breadcrumbs::render('roleCreate') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Role aanmaken</h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('roles.store') }}">

                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="roleName">Naam</label>
                            <input type="text" class="form-control" id="roleName" placeholder="Naam" name="name" value="{{ old('name') }}" required autofocus>
                        </div>

                        <hr>

                        <div class="form-group">
                            <h5 style="font-weight: bold;">Permissies</h5>

                            @foreach($permissions as $perm)
                                <div class="col-md-3">
                                    <div class="checkbox">
                                        <label class="{{ $perm->name }}">
                                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" {{ (is_array(old('permissions')) && in_array($perm->name, old('permissions'))) ? 'checked' : '' }}> {{ $perm->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach

                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Role aanmaken</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop