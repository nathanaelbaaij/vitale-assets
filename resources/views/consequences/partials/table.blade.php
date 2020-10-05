<table class="table table-bordered" style="width: 100%;">
    <tbody>

    <tr>
        <th style="width: 25%">Id</th>
        <td>{{ $consequence->id }}</td>
    </tr>
    <tr>
        <th>Omschrijving</th>
        <td style="white-space: nowrap;">
            <div style="white-space: normal; word-break: break-all; display: block;">
                {{ $consequence->description }}
            </div>
        </td>
    </tr>

    </tbody>
</table>