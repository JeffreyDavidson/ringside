<x-datatable :collection="$retiredTagTeams">
    <thead>
        <th>Id</th>
        <th>Tag Team Name</th>
        <th>Date Retired</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($retiredTagTeams as $tagTeam)
            <tr>
                <td>{{ $tagTeam->id }}</td>
                <td>{{ $tagTeam->name }}</td>
                <td>{{ $tagTeam->retired_at->toDateString() }}</td>
                <td>
                    @include('tagTeams.partials.action-cell', [
                        'tagTeam' => $tagTeam,
                        'actions' => collect([
                            'unretire'
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
