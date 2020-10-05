@extends('adminlte::page')

@section('title', 'Asset categorie wijzigen')

@section('content_header')
    <h1>Asset categorie wijzigen</h1>
    {{ Breadcrumbs::render('category', $category) }}
@stop

@section('content')

    <div class="row">
        <div class="col-md-6">

            <!-- foutmelding -->
            @include('vendor.adminlte.partials.errors')

            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Asset categorie wijzigen</h3>
                    @include('forms.required')
                </div>

                <form method="POST" action="{{ route('categories.update', $category->id) }}">

                    @method('PUT')
                    @csrf
                    <input type="hidden" name="url" value="{{ url()->previous() }}"/>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="required" for="assetCategoryName">Naam</label>
                            <input type="text" class="form-control" id="assetCategoryName" placeholder="Naam"
                                   name="name"
                                   value="{{ $category->name }}" required autofocus>
                        </div>

                        <div class="form-group">
                            <label for="assetParentCategory">Hoofdcategorie</label>
                            <select name="parent_id" class="form-control" id="assetParentCategory">

                                <option value="">Geen</option>

                                @foreach($parentCategories as $parentCategory)
                                    @if($parentCategory->id == $category->parent_id)
                                        <option selected value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                    @else
                                        <option value="{{ $parentCategory->id }}">{{ $parentCategory->name }}</option>
                                    @endif
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="assetCategoryDescription">Beschrijving</label>
                            <textarea name="description" id="assetCategoryDescription" class="form-control" rows="3"
                                      placeholder="Toelichting van de categorie...">{{ $category->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="assetCategoryThreshold">Drempelwaarde</label>
                            <input type="number" step="any" name="threshold" class="form-control" id="assetCategoryThreshold" placeholder="Drempelwaarde" value="{{ $category->threshold }}">
                        </div>

                        <div class="form-group">
                            <label class="required" for="assetCategorySymbol">Symbool</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-addon"><i id="symbol-holder" class="fa va-icon">&#x{{ $category->symbol }}</i> &nbsp;</span>
                                <input type="text" name="symbol" class="form-control" id="assetCategorySymbol" readonly value="{{ $category->symbol }}">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#modal-default">Wijzig...</button>
                                </span>
                            </div>
                        </div>
                        
                        <div class="modal fade in" id="modal-default" style="display: none; padding-right: 17px;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span></button>
                                        <h4 class="modal-title">Kies een symbool</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div id="symbol-container" class="row">
                                            @foreach($symbols as $symbol => $name)
                                                <div class="col-md-6">
                                                    <label>
                                                        <input type="radio" name="symbol-s" value="{!!$symbol!!}"
                                                        {{$symbol==$category->symbol ? "checked" : ""}}> 
                                                        &nbsp;<i class="fa va-icon">&#x{!!$symbol!!}</i> &nbsp;{{$name}}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Annuleren</button>
                                        <button type="button" onclick="update_symbol()" class="btn btn-primary" data-dismiss="modal">Bijwerken</button>
                                    </div>
                                </div>
                                <!-- /.modal-content -->
                            </div>
                            <!-- /.modal-dialog -->

                    </div>

                    <div class="box-footer">
                        <a href="{{ URL::previous() }}" class="btn btn-default">Terug</a>
                        <button type="submit" class="btn btn-success pull-right">Asset Categorie wijzigen</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
rate
@stop

@section('js')
    <script type="text/javascript">
        function update_symbol(){
            value = $("input[type='radio'][name='symbol-s']:checked").val();
            $("input[type='text'][name='symbol']").val(value);
            $("#symbol-holder").replaceWith("<i id='symbol-holder' class='fa va-icon'>&#x" + value + "</i>");

            console.log(value);
        }
    </script>
@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop