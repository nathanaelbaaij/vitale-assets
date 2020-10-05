<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Alle cascades die gekoppeld zijn aan {{ $consequence->name }}</h3>
        </div>
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <div class="row">
                    <div class="col-md-12">

                        <table id="consequence-cascades-table" class="table table-bordered table-hover dataTable">

                            <thead>
                            <tr role="row">
                                <th>Asset van</th>
                                <th>Asset naar</th>
                                <th>Acties</th>
                            </tr>
                            </thead>

                            <tbody>

                            @if($consequence->cascades->count())
                                @foreach($consequence->cascades as $cascade)
                                    <tr>
                                        <td>{{ $cascade->assetFrom->name }}</td>
                                        <td>{{ $cascade->assetFrom->name }}</td>
                                        <td><a href="{{route('cascades.show', $cascade->id)}}" class="btn btn-primary btn-sm">Bekijken</a></td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>

                            <tfoot>
                            <th colspan="1">Asset van</th>
                            <th colspan="1">Asset naar</th>
                            <th colspan="1">Acties</th>
                            </tfoot>

                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('css')
    <link rel="stylesheet" href="/css/app.css">
@stop

@section('js')
    <script src="/js/customDataTables.js"></script>
@stop