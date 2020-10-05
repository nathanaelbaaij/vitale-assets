@extends('adminlte::page')

@section('title', 'Nieuwsberichten aanmaken')

@section('content_header')
    <h1>Nieuwsbericht aanmaken</h1>
    {{ Breadcrumbs::render('newsCreate') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Nieuwsbericht aanmaken
                    </h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('news.store') }}">

                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="username">Titel</label>
                            <input type="text" class="form-control" id="username" placeholder="Titel" name="title" value="{{ old('title') }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="required" for="newspostCategory">Categorie</label>
                            <select name="news_category_id" class="form-control" id="newspostCategory" required>
                                <option selected disabled value="">Selecteer een categorie</option>
                                @foreach($newsCategories as $newsCategory)
                                    <option value="{{$newsCategory->id}}">{{$newsCategory->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="required" for="newsMessage">Bericht</label>
                            <textarea name="message" id="message" class="form-control" rows="3" placeholder="Bericht..." required>{{ old('message') }}</textarea>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Nieuwsbericht toevoegen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop