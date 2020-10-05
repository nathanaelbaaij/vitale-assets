@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard
        <small>Huidige Scenario "{{ $scenario->name }}"</small>
    </h1>
    {{ Breadcrumbs::render('dashboard') }}
@stop

@section('content')

    <!-- session -->
    @include('vendor.adminlte.partials.session')

    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon hover bg-yellow">
                    <i class="fa fa-fw fa-user"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Categorieën</span>
                    <span class="info-box-number">{{ $scenarioCategories->count() }} categorieën</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon hover bg-aqua">
                    <i class="fa fa-fw fa-codepen"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Assets</span>
                    <span class="info-box-number">{{ $scenarioAssetsCount }} assets</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon hover bg-red">
                    <i class="fa fa-fw fa-map-marker"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Breslocaties</span>
                    <span class="info-box-number">{{ $scenarioBreachLocations->count() }} breslocaties</span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon hover bg-green">
                    <i class="fa fa-fw fa-shield"></i>
                </span>
                <div class="info-box-content">
                    <span class="info-box-text">Belastingniveau</span>
                    <span class="info-box-number">{{ $scenarioLoadLevel->code }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-9">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Assets</h3>
                </div>
                <div class="box-body">

                    <table class="table table-bordered dataTable dashboard-asset-scenario-table">
                        <thead>
                        <tr>
                            <th>Asset</th>
                            <th>Categorie</th>
                            <th>Status</th>
                            <th>Maximale waterdiepte</th>
                            <th>Correctie</th>
                            <th>Type</th>
                        </tr>
                        </thead>
                        <tfoot>
                        <tr>
                            <th>Asset</th>
                            <th>Categorie</th>
                            <th>Status</th>
                            <th>Maximale waterdiepte</th>
                            <th>Correctie</th>
                            <th>Type</th>
                        </tr>
                        </tfoot>
                    </table>

                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Belastingsniveaus</h3>
                </div>
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <div>
                                <a class="product-title" href="{{ route('loadlevels.show', $scenarioLoadLevel->id) }}">
                                    {{ $scenarioLoadLevel->code }}
                                </a>
                            </div>
                            <span class="product-description">{{ $scenarioLoadLevel->name }}</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Categorieën</h3>
                </div>
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @if($scenarioCategories->count() > 0)
                            @foreach($scenarioCategories as $scenarioCategory)
                                <li class="item">
                                    <div>
                                        <a class="product-title"
                                           href="{{ route('categories.show', $scenarioCategory->id) }}">
                                            <i class="fa va-icon">&#x{{ $scenarioCategory->symbol }}</i> {{ $scenarioCategory->name }}
                                        </a>
                                    </div>
                                    <span class="product-description">Drempelwaarde: {{ $scenarioCategory->threshold }}</span>
                                </li>
                            @endforeach
                        @else
                            Geen
                        @endif
                    </ul>
                </div>
            </div>

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Breslocaties</h3>
                </div>
                <div class="box-body">
                    <ul class="products-list product-list-in-box">
                        @if($scenarioBreachLocations->count() > 0)
                        @foreach($scenarioBreachLocations as $scenarioBreachLocation)
                            <li class="item">
                                <div>
                                    <a class="product-title"
                                       href="{{ route('breaches.show', $scenarioBreachLocation->id) }}">
                                        {{ $scenarioBreachLocation->longname }}
                                    </a>
                                </div>
                                <span class="product-description">Dijkring: {{ $scenarioBreachLocation->dykering }}</span>
                            </li>
                        @endforeach
                        @else
                            Geen
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script src="{{ asset('js/dashboardScenario.js') }}"></script>
@stop