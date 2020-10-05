<div class="row">
    <div class="col-md-12">
        <div class="box">

            <div class="box-header">
                <h3 class="box-title">Laatste wijzigingen</h3>
            </div>

            <div class="box-body">

                <div class="panel-group" id="accordion">
                    @php
                        $index = 0;
                    @endphp

                    @foreach ($logs as $log)

                        @php
                            $d = strval($log->lb_json_data);
                            $obj =json_decode($d);
                            $index++;
                        @endphp

                        @php
                            //set time language to dutch
                            setlocale(LC_TIME, "nl_NL");
                            \Carbon\Carbon::setLocale('nl');
                            //asset changed x time ago
                            $objUpdatedAt = \Carbon\Carbon::parse($obj->updated_at)->diffForHumans();
                            //asset changed timestamp
                            $timeStamp = \Carbon\Carbon::parse($obj->updated_at)->format('d F Y H:i:s');
                        @endphp

                        <div class="panel panel-default">

                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion"
                                       href="#{{$log->lb_id}}">
                                        <img style="width:15px; height:15px;" class="img-circle"
                                             src="{{asset('uploads/avatars\\').$log->image_url}}"/>{{$log->username}}
                                        , {{ $objUpdatedAt }} ({{ $timeStamp }})
                                    </a>
                                </h4>
                            </div>

                            <div id="{{$log->lb_id}}"
                                 class="panel-collapse {{$index == 1 ? 'collapse-in' : 'collapse'}}">
                                <div class="panel-body table-responsive">

                                    <div class="text-center">
                                        <div class="well well-sm">
                                            <img style="width:70px; height:70px;"
                                                 class="img-circle box_shadow"
                                                 src="{{asset('uploads/avatars\\').$log->image_url}}"/>
                                            <p>Gewijzigd door: {{$log->username}}
                                                op {{$obj->updated_at}}</p>
                                            <p>De asset data zal worden vervangen door de data hieronder</p>
                                        </div>
                                    </div>


                                    <table class="table table-striped">
                                        <thead>
                                        <tr>
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
                                       href="{{route('logbook.action.revert', $obj->id)}}">Wijziging
                                        verwijderen</a>
                                    <a class="btn btn-warning btn-sm pull-right"
                                       href="{{route('logbook.action.revert', $obj->id)}}">Wijziging
                                        terugzetten</a>

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</div>