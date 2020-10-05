@extends('adminlte::page')

@section('title', 'Kaart')

@section('content')

    <!-- session -->
    @include('vendor.adminlte.partials.session')

    <!-- map -->
    <div id="map">
        <div id="filter" class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Assets Toolbox</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool cat-reset"><i class="fa fa-trash-o"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                @foreach ($categories as $category)
                    <div class="box-group" id="accordion">
                        <div class="panel box box-primary">
                            <div class="box-header with-border">
                                {{--<h3 class="box-title">--}}
                                    <a class="collapsed d-block" data-toggle="collapse" data-parent="#accordion"
                                       href="#{{ $category->id }}" aria-expanded="false">
                                        {{ $category->name }}
                                    </a>
                                {{--</h3>--}}
                            </div>
                            <div id="{{ $category->id }}" class="panel-collapse collapse" aria-expanded="false">
                                <div class="box-body">
                                    @if(count($category->children)>0)
                                        @foreach($category->children as $catChild)
                                            <div id="layer{{ $catChild->id }}" class="checkbox">
                                                <label for="{{ $catChild->name }}">
                                                    <input id="{{ $catChild->name }}" class="category-checkbox" name="{{ $catChild->id }}" type="checkbox"/>
                                                    <i id="cat-{{ $catChild->id }}" class="fa va-icon">&#x{{ trim($catChild->symbol) }}</i>
                                                    <span class="category-checkbox-name" unselectable="on">{{ $catChild->name }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                    @else
                                        <div id="layer{{ $category->id }}" class="checkbox">
                                            <label for="{{ $category->name }}">
                                                <input id="{{ $category->name }}" class="category-checkbox" name="{{ $category->id }}" type="checkbox"/>
                                                <i id="cat-{{ $category->id }}" class="fa va-icon">&#x{{ trim($category->symbol) }}</i>
                                                <span class="category-checkbox-name" unselectable="on">Alles</span>
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="box-group" id="accordion">
                    <div class="panel box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Belastingniveaus</h3>
                        </div>
                        <div id="loadlevel" class="box-body">
                            <div class="btn-group">
                                @foreach($loadlevels as $loadlevel)
                                    <button id="ll-{{ $loadlevel->id }}" data-loadlevel="{{ $loadlevel->id }}" type="button" class="btn btn-default">{{ $loadlevel->code }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <a type="button" class="btn btn-block btn-danger reset bl-reset">
                    Breslocatie reset
                </a>
            </div>
        </div>
    </div>

    <div class="arrow_box" id="popup-container"></div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">
    <link rel="stylesheet" href="/css/ol.css">
    <style>
        #map {
            width: 100vw;
            height: calc(100vh - 50px);
            position: absolute;
            left: 0;
            top: 50px;
            right: 0;
            bottom: 0;
        }

        #map #filter {
            position: absolute;
            top: 1em;
            right: 1em;
            max-height: calc(100% - 60px);
            min-width: 240px;
            z-index: 100;
            margin: 0;
            width: auto;
            overflow-y: auto;
            background: rgba(255,255,255,0.90) !important;
        }

        /*#map #loadlevel {*/
            /*position: absolute;*/
            /*z-index: 100;*/
            /*bottom: 1em;*/
            /*left: 50%;*/
            /*width: auto;*/
            /*transform: translateX(-50%);*/
        /*}*/

        #map #reset {
            position: absolute;
            top: 5em;
            left: 10px;
            z-index: 100;
            width: auto;
        }

        #map .d-block {
            display: block;
        }

        /* Dont select the checkbox text */
        #map .category-checkbox-name {
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            -o-user-select: none;
            user-select: none;
        }

        .content {
            padding: 0;
        }

        .content-header {
            display: none;
        }

        .arrow_box {
            border-radius: 5px;
            padding: 10px;
            position: relative;
            background: rgba(255,255,255,0.90);
            border: 1px solid #003c88;
        }

        .arrow_box:after, .arrow_box:before {
            top: 100%;
            left: 50%;
            border: solid transparent;
            content: " ";
            height: 0;
            width: 0;
            position: absolute;
            pointer-events: none;
        }

        .arrow_box:after {
            border-color: rgba(255, 255, 255, 0);
            border-top-color: #fff;
            border-width: 10px;
            margin-left: -10px;
        }

        .arrow_box:before {
            border-color: rgba(153, 153, 153, 0);
            border-top-color: #003c88;
            border-width: 11px;
            margin-left: -11px;
        }

        .checkbox-disabled {
            color: #eee;
        }
    </style>
@stop

@section('js')
    <script src="{{ asset('js/ol-debug.js') }}"></script>
    <script src="{{ asset('js/proj4.js') }}"></script>
    <script src="{{ asset('js/map.js') }}"></script>
@stop
