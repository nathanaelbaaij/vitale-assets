@extends('adminlte::page')

@section('title', 'Cascade aanmaken')

@section('content_header')
    <h1>Cascade aanmaken</h1>
    {{ Breadcrumbs::render('cascadeCreate') }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Toevoegen
                    </h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('cascades.store') }}">

                    @csrf

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required" for="assetFrom">Asset van</label>
                                    <select name="asset-from" class="form-control" id="assetFrom" required autofocus>

                                        <option value="" selected disabled>Geen</option>

                                        @foreach($assets as $asset)
                                            @if(request()->has('aid') && (request()->get('aid') == $asset->id))
                                                <option value="{{ $asset->id }}" selected>{{ $asset->id }} {{ $asset->name }}</option>
                                            @else
                                                <option value="{{ $asset->id }}">{{ $asset->id }} {{ $asset->name }}</option>
                                            @endif
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="required" for="assetTo">Asset naar</label>
                                    <select name="asset-to" class="form-control" id="assetTo" required>

                                        <option value="" selected disabled>Geen</option>

                                        @foreach($assets as $asset)
                                            <option value="{{ $asset->id }}">{{ $asset->id }} {{ $asset->name }}</option>
                                        @endforeach

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="required" for="consequenceSelect">Wat voor consequentie heeft dit
                                        cascade effect?</label>
                                    <select name="consequence" class="form-control select2-consequence"
                                            id="consequenceSelect" required>
                                        <option value="" selected disabled></option>
                                        @foreach($consequences as $consequence)
                                            <option value="{{ $consequence->id }}">{{ $consequence->description }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Cascade toevoegen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script>
        $(document).ready(function () {

            $('#assetFrom').select2();
            $('#assetTo').select2();

            $('.select2-consequence').select2({
                placeholder: 'Kies een consequentie of maak er een aan',
                tags: true,
            });
        });
    </script>
@stop
