<x-datatable :collection="$retiredManagers">
    <thead>
        <th>Id</th>
        <th>Manager Name</th>
        <th>Date Retired</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($retiredManagers as $manager)
            <tr>
                <td>{{ $manager->id }}</td>
                <td>{{ $manager->full_name }}</td>
                <td>{{ $manager->retired_at->toDateString() }}</td>
                <td>
                    @include('managers.partials.action-cell', [
                        'manager' => $manager,
                        'actions' => collect([
                            'unretire'
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
