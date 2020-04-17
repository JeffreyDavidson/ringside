<x-datatable :collection="$activeStables">
    <thead>
        <th>Id</th>
        <th>Stable Name</th>
        <th>Date Introduced</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($activeStables as $stable)
            <tr>
                <td>{{ $stable->id }}</td>
                <td>{{ $stable->name }}</td>
                <td>{{ $stable->activated_at->toDateString() }}</td>
                <td>{{ $stable->status->label() }}</td>
                <td>
                    {{-- @include('stables.partials.action-cell', [
                        'stable' => $stable,
                        'actions' => collect([

                        ])
                    ]) --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>

