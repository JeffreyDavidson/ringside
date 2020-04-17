<x-datatable :collection="$inactiveStables">
    <thead>
        <th>Id</th>
        <th>Stable Name</th>
        <th>Date Deactivated</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($inactiveStables as $stable)
            <tr>
                <td>{{ $stable->id }}</td>
                <td>{{ $stable->name }}</td>
                <td>{{ $stable->deactivated_at->toDateString() }}</td>
                <td>
                    @include('stables.partials.action-cell', [
                        'stable' => $stable,
                        'actions' => collect([
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
