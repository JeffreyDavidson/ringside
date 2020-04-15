<x-table>
    <thead>
        <th>Id</th>
        <th>Wrestler Name</th>
        <th>Hometown</th>
        <th>Date Released</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($releasedWrestlers as $wrestler)
            <tr>
                <td>{{ $wrestler->id }}</td>
                <td>{{ $wrestler->name }}</td>
                <td>{{ $wrestler->hometown }}</td>
                <td>{{ $wrestler->released_at->toDateString() }}</td>
                <td>
                    @include('wrestlers.partials.action-cell', [
                        'wrestler' => $wrestler,
                        'actions' => collect([
                            'employ'
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-table>
