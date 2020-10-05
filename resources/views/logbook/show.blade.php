@extends('adminlte::page')

@section('title', 'Logboek wijzigingen')

@section('content_header')
@stop

@section('content')

    @include('vendor.adminlte.partials.session')

    <div class="row text-center">

        <div class="col-md-8">
            <input type="text" id="myInput" onkeyup="search()" placeholder="Zoek naar asset..">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Laatste wijzigingen in assets tot 7 dagen geleden</h3>
                </div>

                <div class="box-body">

                    <div id="search" class="panel-group" id="accordion">

                        @php
                            $index = 0;
                        @endphp

                        @foreach ($logs as $log)
                            @php
                                $d = strval($log->lb_json_data);
                                $obj =json_decode($d);
                                $index++;

                                $timeStamp = \Carbon\Carbon::parse($obj->updated_at)->format('d F Y H:i:s');
                            @endphp

                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#{{$log->lb_id}}">

                                            <span>
                                            Asset {{$log->name}} is gewijzigd op {{ $timeStamp }} door <img
                                                    style="width:15px; height:15px; border-radius:50%;"
                                                    class="img-circle"
                                                    src="{{asset('uploads/avatars\\').$log->image_url}}"/>{{$log->username}}
                                            </span>
                                        </a>
                                    </h4>
                                </div>


                                <div id="{{$log->lb_id}}"
                                     class="panel-collapse coll-class {{$index == 1 ? 'collapse-in' : 'collapse'}}">

                                    <div class="panel-body table-responsive">

                                        <div class="text-center">
                                            <div class="well well-sm">
                                                <img style="width:70px; height:70px; border-radius:50%;"
                                                     class="img-circle box_shadow"
                                                     src="{{asset('uploads/avatars\\').$log->image_url}}"/>
                                                <p>Gewijzigd door: {{$log->username}} op {{$obj->updated_at}}</p>
                                                <p>De asset data zal worden vervangen door de data hieronder</p>
                                            </div>
                                        </div>


                                        <table class="table table-striped text-center">
                                            <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>ID</th>
                                                <th>Naam</th>
                                                <th>Beschrijving</th>
                                                <th>Categorie</th>
                                                <th>X-coordinaat</th>
                                                <th>Y-coordinaat</th>
                                                <th>Drempelwaarde</th>
                                            </tr>
                                            </thead>
                                            <tbody>



                                            <tr>
                                                <td>Oud</td>
                                                <td>
                                                    <a href="{{route('assets.show', $obj->id)}}">{{$obj->id == null ? "Niet beschikbaar" : $obj->id}}</a>
                                                </td>
                                                <td>{{$log->name == null ? "Niet beschikbaar" : $log->name}}</td>
                                                <td>{{$log->description == null ? "Niet beschikbaar" : $log->description}}</td>
                                                <td>{{$log->category_id == null ? "Niet beschikbaar" : $log->category_id}}</td>
                                                <td>{{$log->x_coordinate == null ? "Niet beschikbaar" : $log->x_coordinate}}</td>
                                                <td>{{$log->y_coordinate == null ? "Niet beschikbaar" : $log->y_coordinate}}</td>
                                                <td>{{$log->threshold_correction == null ? "Niet beschikbaar" : $log->threshold_correction}}</td>
                                            </tr>

                                            <tr>
                                                <td>Nieuw</td>
                                                <td>
                                                    <a href="{{route('assets.show', $obj->id)}}">{{$obj->id == null ? "Niet beschikbaar" : $obj->id}}</a>
                                                </td>
                                                <td>{{$obj->name == null ? "Niet beschikbaar" : $obj->name}}</td>
                                                <td>{{$obj->description == null ? "Niet beschikbaar" : $obj->description}}</td>
                                                <td>{{$obj->category_id == null ? "Niet beschikbaar" : $obj->category_id}}</td>
                                                <td>{{$obj->x_coordinate == null ? "Niet beschikbaar" : $obj->x_coordinate}}</td>
                                                <td>{{$obj->y_coordinate == null ? "Niet beschikbaar" : $obj->y_coordinate}}</td>
                                                <td>{{$obj->threshold_correction == null ? "Niet beschikbaar" : $obj->threshold_correction}}</td>
                                            </tr>

                                            </tbody>
                                        </table>

                                        <a class="btn btn-danger btn-sm pull-right"
                                           href="{{route('logbook.action.delete', $obj->id)}}">Wijziging verwijderen</a>
                                        <a class="btn btn-warning btn-sm pull-right"
                                           href="{{route('logbook.action.revert', $obj->id)}}">Wijziging terugzetten</a>

                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box">

                <div class="box-header">
                    <h3 class="box-title">Laatste gemaakte terugzettingen</h3>
                </div>

                <div class="box-body">

                    <p>De laatst gemaakte wijzigingen</p>


                    <table class="table table-bordered text-center">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Tijd</th>
                        </tr>
                        </thead>

                        @foreach ($reverteds as $reverted)
                            <tbody>
                            <tr>
                                <td><a href="{{route('assets.show', $reverted->asset_id)}}">{{$reverted->asset_id}}</a>
                                </td>
                                <td><a href="{{route('assets.show', $reverted->asset_id)}}">{{$reverted->name}}</a></td>
                                <td>{{$reverted->updated_at}}</td>
                            </tr>
                            </tbody>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/app.css">

    <style>
        .btn {
            margin-left: 5px;
        }

        #myInput {
            background-image: url('https://www.w3schools.com/css/searchicon.png'); /* Add a search icon to input */
            background-position: 10px 12px; /* Position the search icon */
            background-repeat: no-repeat; /* Do not repeat the icon image */
            width: 100%; /* Full-width */
            font-size: 16px; /* Increase font-size */
            padding: 12px 20px 12px 40px; /* Add some padding */
            border: 1px solid #ddd; /* Add a grey border */
            margin-bottom: 12px; /* Add some space below the input */
        }

    </style>
@stop

@section('js')
    <script src="/js/customDataTables.js"></script>

    <script>
        function search() {
            // Declare variables
            var input, filter, table, tr, td, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("search");
            tr = table.getElementsByTagName("div");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("span")[0];

                if (td) {
                    if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
            var active = document.querySelector(".coll-class");
            active.classList.remove("collapse-in");
            active.classList.add("collapse");
        }

    </script>


@stop