<div class="row">
    <div class="col-sm-12">
        <table id="example2" class="table table-bordered table-hover">

            <thead>
            <tr>
                <th>@sortablelink('name', 'naam')</th>
                <th>Beschrijving</th>
                <th>Subcategoriën</th>
                <th>Assets</th>
            </tr>
            </thead>

            <tbody id="results">

            @foreach($categories as $category)

                <tr>
                    <td>
                        <a href="{{ route('categories.show', ['category' => $category]) }}">{{ $category->name }}</a>
                    </td>
                    <td>
                        @if($category->description)
                            {{ $category->description }}
                        @else
                            {{ '(geen beschrijving)' }}
                        @endif
                    </td>
                    <td>{{ count($category->children) }}</td>
                    <td>{{ count($category->assets) }}</td>
                </tr>

            @endforeach

            </tbody>

            <tfoot>

            </tfoot>

        </table>
    </div>
</div>