@extends('adminlte::page')

@section('title', 'Nieuwsbericht verwijderen')

@section('content_header')
    <h1>Nieuwsbericht verwijderen</h1>
    {{ Breadcrumbs::render('news', $newspost) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Weet u zeker dat u nieuwsbericht {{ $newspost->title }} wilt verwijderen?</h3>
                </div>

                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th style="width: 25%;">Id</th>
                            <td>{{ $newspost->id }}</td>
                        </tr>
                        <tr>
                            <th>Titel</th>
                            <td>{{ $newspost->title }}</td>
                        </tr>
                        <tr>
                            <th>Bericht</th>
                            <td style="word-wrap: break-word; word-break: break-all; white-space: normal;">{{ $newspost->message }}</td>
                        </tr>
                        <tr>
                            <th>Gebruiker</th>
                            <td>{{ $newspost->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Nieuwscategorie</th>
                            <td>{{ $newspost->category->name }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="box-footer">
                    <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>

                    <div class="pull-right">

                        <form action="{{ route('news.destroy', $newspost->id) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button class="btn btn-danger" type="submit">
                                Definitief verwijderen
                            </button>
                        </form>

                    </div>

                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop