<div class="col-md-12">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Alle assets die gekoppeld zijn aan {{ $category->name }}</h3>
            @can('asset-create')
                <a href="{{route('assets.create')}}" class="btn btn-success pull-right">Asset toevoegen</a>
            @endcan
        </div>
        <div class="box-body">
            <div id="example2_wrapper" class="dataTables_wrapper form-inline dt-bootstrap">

                <div class="row">
                    <div class="col-md-12">

                        <table id="category-assets-table" class="table table-bordered table-hover dataTable">

                            <thead>
                            <tr role="row">
                                <th class="col-md-4">Naam</th>
                                <th class="col-md-8">Beschrijving</th>
                            </tr>
                            </thead>

                            <tbody>

                            @if($assets->count())
                                @foreach($assets as $asset)
                                    <tr>
                                        <td>
                                            <a href="{{ route('assets.show', $asset->id)}}">{{ $asset->name }}</a>
                                        </td>
                                        <td>
                                            @if($asset->description)
                                                {{ $asset->description }}
                                            @else
                                                {{ '(geen beschrijving)' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>

                            <tfoot>
                            <th>Naam</th>
                            <th>Beschrijving</th>
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