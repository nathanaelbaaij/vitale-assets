@extends('adminlte::page')

@section('title', 'Gebruiker wijzigen')

@section('content_header')
    <h1>Gebruiker wijzigen</h1>
    {{ Breadcrumbs::render('user', $user) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Gebruiker wijzigen</h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('users.update', $user->id) }}">

                    @method('PUT')
                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="name">Naam</label>
                            <input type="text" class="form-control" id="name" placeholder="Naam" name="name"
                                   value="{{ $user->name }}">
                        </div>

                        <div class="form-group">
                            <label class="required" for="email">E-mailadres</label>
                            <input type="email" class="form-control" id="email" placeholder="E-mailadres" name="email"
                                   value="{{ $user->email }}">
                        </div>

                        <div class="form-group">
                            <label class="required" for="password">Wachtwoord</label>
                            <input type="password" class="form-control" id="password" placeholder="Wachtwoord"
                                   name="password" value="{{ $user->password }}">
                        </div>

                        <div class="form-group">
                            <label class="required" for="role">Role</label>

                            <select name="role[]" class="form-control select2" id="role" multiple="multiple">
                                @foreach($roles as $role)
                                    @if(array_has($userRole, $role))
                                        <option value="{{ $role }}" selected="selected">
                                            {{ $role }}
                                        </option>
                                    @else
                                        <option value="{{ $role }}">
                                            {{ $role }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <hr>

                        <div class="form-group">
                            <label for="company">Bedrijfsnaam</label>
                            <input type="text" class="form-control" id="company" placeholder="" name="company" value="{{ $user->company }}">
                        </div>

                        <div class="row form-group">
                            <div class="col-md-8">
                                <label for="adres">Adres</label>
                                <input type="text" class="form-control" id="adres" placeholder="" name="adres" value="{{ $user->adres }}">
                            </div>
                            <div class="col-md-4">
                                <label for="houseNumber">Huisnummer</label>
                                <input type="text" class="form-control" id="houseNumber" placeholder="Bijv. 10-C" name="house_number" value="{{ $user->house_number }}">
                            </div>
                        </div>

                        <div class="row form-group">
                            <div class="col-md-8">
                                <label for="city">Stad</label>
                                <input type="text" class="form-control" id="postal" placeholder="" name="city" value="{{ $user->city }}">
                            </div>
                            <div class="col-md-4">
                                <label for="postal">Postcode</label>
                                <input type="text" class="form-control" id="postal" placeholder="" name="postal" value="{{ $user->postal }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefoonnumer</label>
                            <input type="text" class="form-control" id="phone" placeholder="" name="phone" value="{{ $user->phone }}">
                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Gebruiker wijzigen</button>
                    </div>

                </form>

                @section('js')
                    <script type="text/javascript">
                        $(document).ready(function () {
                            $('.select2').select2({width: '100%'});
                        });
                    </script>
                @stop

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop