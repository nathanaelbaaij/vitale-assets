@extends('adminlte::page')

@section('title', 'Nieuwsbericht wijzigen')

@section('content_header')
    <h1>Nieuwsbericht wijzigen</h1>
    {{ Breadcrumbs::render('news', $newspost) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            @include('vendor.adminlte.partials.errors')

            <div class="box">

                <div class="box-header with-border">
                    <h3 class="box-title">Wijzigen van nieuwsbericht "{{$newspost->title}}"</h3>
                    @include('forms.required')
                </div>

                <form action="{{route('news.update', $newspost->id)}}" method="post">

                    @method('PUT')
                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="newspostTitle">Titel</label>
                            <input type="text" class="form-control" id="newspostTitle" placeholder="Titel" name="title" value="{{ $newspost->title }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label class="required" for="newspostCategory">Categorie</label>
                            <select name="news_category_id" class="form-control" id="newspostCategory" required>
                                @foreach($newsCategories as $newsCategory)
                                    @if($newsCategory->id == $newspost->news_category_id)
                                        <option selected value="{{$newsCategory->id}}">{{$newsCategory->name}}</option>
                                    @else
                                        <option value="{{$newsCategory->id}}">{{$newsCategory->name}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="required" for="newspostMessage">Bericht</label>
                            <textarea name="message" id="newspostMessage" class="form-control" rows="3" placeholder="Bericht..." required>{{ $newspost->message }}</textarea>
                        </div>

                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Nieuwsbericht wijzigen</button>
                    </div>
                </form>

            </div>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop