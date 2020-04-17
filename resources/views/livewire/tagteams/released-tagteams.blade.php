<x-datatable :collection="$releasedTagTeams">
    <thead>
        <th>Id</th>
        <th>Tag Team Name</th>
        <th>Date Released</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($releasedTagTeams as $tagTeam)
            <tr>
                <td>{{ $tagTeam->id }}</td>
                <td>{{ $tagTeam->name }}</td>
                <td>{{ $tagTeam->released_at->toDateString() }}</td>
                <td>
                    @include('tagTeams.partials.action-cell', [
                        'tagTeam' => $tagTeam,
                        'actions' => collect([
                            'employ'
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
