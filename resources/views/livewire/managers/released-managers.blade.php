<x-datatable :collection="$releasedManagers">
    <thead>
        <th>Id</th>
        <th>Manager Name</th>
        <th>Date Released</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($releasedManagers as $manager)
            <tr>
                <td>{{ $manager->id }}</td>
                <td>{{ $manager->full_name }}</td>
                <td>{{ $manager->released_at->toDateString() }}</td>
                <td>
                    @include('managers.partials.action-cell', [
                        'manager' => $manager,
                        'actions' => collect([
                            'employ'
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
