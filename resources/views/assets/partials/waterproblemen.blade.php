<div class="col-md-8">
    <div class="box">

        <div class="box-header with-border">
            <h3 class="box-title">Gevolg van waterproblemen</h3>
        </div>

        <div class="box-body">
            <div class="nav-tabs-custom tabs-success">
                <ul class="nav nav-tabs">
                    <li class="active">
                        <a href="#waterdepths" data-toggle="tab">Waterdieptes</a>
                    </li>
                    <li>
                        <a href="#cascade" data-toggle="tab">Cascade</a>
                    </li>
                </ul>
                <div class="tab-content">

                    <div class="active tab-pane" id="waterdepths">

                        <div class="nav-tabs-custom">

                            <ul class="nav nav-tabs">
                                @foreach($loadLevels as $count => $loadLevel)
                                    <li @if($count == 1) class="active" @endif>
                                        <a href="#loadlevel-{{ $loadLevel->id }}" data-toggle="tab"
                                           data-loadlevel="{{ $loadLevel->id }}" data-asset="{{ $asset->id }}"
                                           aria-expanded="false">
                                            {{ $loadLevel->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>

                            <div class="tab-content">

                                @foreach($loadLevels as $count => $loadLevel)
                                    <div @if($count == 1) class="tab-pane active" @else class="tab-pane" @endif id="loadlevel-{{ $loadLevel->id }}">
                                        <table class="table table-bordered dataTable asset-breachlocation-waterdepth-table">
                                            <thead>
                                            <tr>
                                                <th>Breslocatie</th>
                                                <th>Waterdiepte</th>
                                                <th>Status</th>
                                            </tr>
                                            </thead>
                                            <tfoot>
                                            <tr>
                                                <th>Breslocatie</th>
                                                <th>Waterdiepte</th>
                                                <th>Status</th>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                @endforeach

                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="cascade">
                        @if(!$cascades->isEmpty())
                            <h4>
                                Cascades van: asset {{$asset->id . " " . $asset->name}}
                            </h4>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Huidige asset</th>
                                    <th>Afhankelijke asset</th>
                                    <th>Consequentie</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cascades as $cascade)
                                    @if($cascade->assetFrom->id == $asset->id)
                                        <tr>
                                            <td>{{$cascade->assetFrom->id . ": " . $cascade->assetFrom->name}}</td>
                                            <td>
                                                <a href="{{ route('assets.show', ['id' => $cascade->assetTo->id]) }}">{{$cascade->assetTo->id . ": " . $cascade->assetTo->name}}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('consequences.show', ['id' => $cascade->consequence->id]) }}">{{ $cascade->consequence->description }}</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Afhankelijke asset</th>
                                    <th>Huidige asset</th>
                                    <th>Consequentie</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($cascades as $cascade)
                                    @if($cascade->assetTo->id == $asset->id)
                                        <tr>
                                            <td>
                                                <a href="{{ route('assets.show', ['id' => $cascade->assetFrom->id]) }}">{{$cascade->assetFrom->id . ": " . $cascade->assetFrom->name}}</a>
                                            </td>
                                            <td>{{$cascade->assetTo->id . ": " . $cascade->assetTo->name}}</td>
                                            <td>
                                                <a href="{{ route('consequences.show', ['id' => $cascade->consequence->id]) }}">{{ $cascade->consequence->description }}</a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>

                        @else
                            Geen cascades beschikbaar.
                        @endif

                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('cascades.create', ['aid' => $asset->id]) }}" class="btn btn-success btn-sm pull-right">
                                    Cascade toevoegen
                                </a>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
</div>