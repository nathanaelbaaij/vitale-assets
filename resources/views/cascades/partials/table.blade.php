<table class="table table-bordered">
    <tbody>
    <tr>
        <th style="width: 25%;">Id</th>
        <td>{{ $cascade->id }}</td>
    </tr>
    <tr>
        <th>Asset van</th>
        <td>
            <a href="{{ route('cascades.show', ['id' => $cascade->assetFrom->id]) }}">{{ $cascade->assetFrom->id . ': ' . $cascade->assetFrom->name }}</a>
        </td>
    </tr>
    <tr>
        <th>Asset naar</th>
        <td>
            <a href="{{ route('cascades.show', ['id' => $cascade->assetTo->id]) }}">{{ $cascade->assetTo->id . ': ' . $cascade->assetTo->name }}</a>
        </td>
    </tr>
    <tr>
        <th>Consequentie</th>
        <td>
            <a href="{{ route('consequences.show', ['id' => $cascade->consequence->id]) }}">{{ $cascade->consequence->description }}</a>
        </td>
    </tr>
    </tbody>
</table>