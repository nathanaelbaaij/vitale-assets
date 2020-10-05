{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Asset categorie')

@section('content_header')
    <h1>{{ $category->name }}</h1>
    {{ Breadcrumbs::render('category', $category) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">Asset categorie</h3>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered">
                                <tbody>

                                <tr>
                                    <th style="width: 25%;">Naam</th>
                                    <td>{{ $category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Hoofdcategorie</th>
                                    <td>
                                        @if($category->parent)
                                            <a href="{{ route('categories.show', ['id' => $category->parent->id]) }}">{{ $category->parent->name }}</a>
                                        @else
                                            {{ '(geen hoofdcategorie)' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Beschrijving</th>
                                    <td>
                                        @if($category->description)
                                            {{ $category->description }}
                                        @else
                                            {{ '(geen beschrijving)' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Drempelwaarde</th>
                                    <td>
                                        @if($category->threshold)
                                            {{ $category->threshold }}
                                        @else
                                            {{ '(geen drempelwaarde)' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Symbool</th>
                                    <td>
                                        @if($category->symbol)
                                            <i class="fa va-icon">&#x{{ trim($category->symbol) }}</i> {{ $category->symbol }}
                                        @else
                                            {{ '(geen symbool)' }}
                                        @endif
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="box-footer">
                            <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                            <div class="pull-right">
                                @can('asset-category-edit')
                                    <a href="{{ route('categories.edit', ['id' => $category->id]) }}" type="button"
                                       class="btn btn-warning">
                                        Wijzigingen
                                    </a>
                                @endcan
                                @can('asset-category-delete')
                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#delete">Verwijderen</button>
                                @endcan
                            </div>

                        </div>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">Asset subcategorie(ën) van {{ $category->name }}</h3>
                        </div>

                        <div class="box-body">
                            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                                @if(count($category->children))

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="example2" class="table table-bordered table-hover">

                                                <thead>
                                                <tr>
                                                    <th>Naam</th>
                                                    <th>Symbol</th>
                                                    <th>Beschrijving</th>
                                                    <th>Subcategoriën</th>
                                                    <th>Assets</th>
                                                </tr>
                                                </thead>

                                                <tbody>
                                                @foreach($category->children as $categoryChild)
                                                    <tr>
                                                        <td>
                                                            <a href="{{ route('categories.show', ['category' => $categoryChild]) }}">{{ $categoryChild->name }}</a>
                                                        </td>
                                                        <td>
                                                            <i class="fa va-icon">&#x{{ trim($categoryChild->symbol) }}</i>
                                                        </td>
                                                        <td>
                                                            @if($categoryChild->description)
                                                                {{ $categoryChild->description }}
                                                            @else
                                                                {{ '(geen beschrijving)' }}
                                                            @endif
                                                        </td>
                                                        <td>{{ count($categoryChild->children) }}</td>
                                                        <td>{{ count($categoryChild->assets) }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>

                                                <tfoot>

                                                </tfoot>

                                            </table>
                                        </div>
                                    </div>

                                @else

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <p>Geen asset subcategorieën beschikbaar.</p>
                                        </div>
                                    </div>

                                @endif

                                <div class="row">
                                    <div class="col-sm-6">

                                    </div>
                                    <div class="col-sm-6">
                                        <div class="dataTables_info pull-right">
                                            @can('asset-category-create')
                                                <a href="{{ route('categories.create', ['hc' => $category->id]) }}" class="btn btn-success btn-sm">Asset categorie toevoegen</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <!--assets-->
            @include('categories.partials.assets-index')
        </div>
    </div>

    @includeIf('modals.delete', ['name' => 'categorie', 'action' => route('categories.destroy', $category->id)])

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop