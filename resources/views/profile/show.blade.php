@extends('adminlte::page')

@section('title', 'Profiel')

@section('content_header')
    <h1>{{ $user->name }}</h1>
    {{ Breadcrumbs::render('user', $user) }}
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-3">
            <div class="box box-primary">
                <div class="box-body box-profile">
                    <img class="profile-user-img img-responsive img-circle" src="{{ $user->avatar }}"
                         alt="User profile picture">

                    <h3 class="profile-username text-center">{{ $user->name }}</h3>
                    <p class="text-muted text-center">
                        @foreach ($user->roles as $role)
                            <span class="label label-primary">{{ $role->name }}</span>
                        @endforeach
                    </p>

                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item">
                            <b><i class="fa fa-envelope margin-r-5"></i> Email</b> <a
                                    class="pull-right">{{ $user->email }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fa fa-phone margin-r-5"></i> Telefoonnummer</b> <a
                                    class="pull-right">{{ $user->phone }}</a>
                        </li>
                        <li class="list-group-item">
                            <b><i class="fa fa-pencil margin-r-5"></i> Aangemaakt op</b> <a
                                    class="pull-right">{{ $user->created_at->format('d-m-Y') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="box box-primary">

                <div class="box-header with-border">
                    <h3 class="box-title">Instituut</h3>
                </div>

                <div class="box-body">
                    <strong><i class="fa fa-building margin-r-5"></i> Naam</strong>
                    <p class="text-muted">
                        {{ $user->company }}
                    </p>
                    <hr>
                    <strong><i class="fa fa-map-marker margin-r-5"></i> Locatie</strong>
                    <p class="text-muted">
                        {{ $user->adres }} {{ $user->house_number }}, {{ $user->postal }} {{ $user->city }}
                    </p>
                </div>

            </div>
        </div>
        <div class="col-md-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#timeline" data-toggle="tab" aria-expanded="true">Tijdlijn</a></li>
                    <li><a href="#profile" data-toggle="tab" aria-expanded="false">Profiel</a></li>
                    <li><a href="#account" data-toggle="tab" aria-expanded="false">Account</a></li>
                </ul>
                <div class="tab-content">
                    <!-- timeline -->
                    <div class="tab-pane active" id="timeline">
                        @includeIf("profile.partials.timeline")
                    </div>
                    <!-- /timeline -->

                    <!-- profile settings -->
                    <div class="tab-pane" id="profile">
                        <div class="row">
                            <div class="col-md-6">
                                @includeIf("profile.partials.profile_form_edit")
                            </div>
                            <div class="col-md-6">
                                @includeIf("profile.partials.avatar")
                            </div>
                        </div>

                    </div>
                    <!-- /profile settings -->

                    <!-- account settings -->
                    <div class="tab-pane" id="account">
                        @includeIf("profile.partials.account_form_edit")
                    </div>
                    <!-- /account settings -->
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.select2').select2({width: '100%'});

            var url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href="#' + url.split('#')[1] + '"]').tab('show');
                window.scrollTo(0, 0);
            }

            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                window.location.hash = e.target.hash;
            });
        });
    </script>
@stop
