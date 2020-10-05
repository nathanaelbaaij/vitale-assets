@extends('adminlte::page')

@section('title', 'Consequentie wijzigen')

@section('content_header')
    <h1>Consequentie wijzigen</h1>
    {{ Breadcrumbs::render('consequence', $consequence) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Aanpassen</h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('consequences.update', $consequence->id) }}">

                    @method('PUT')
                    @csrf

                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="consequenceDescription">Omschrijving</label>
                            <textarea name="description" id="consequenceDescription" class="form-control" rows="3"
                                      placeholder="Toelichting van de consequentie..." required autofocus>{{ $consequence->description }}</textarea>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Consequentie wijzigen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop