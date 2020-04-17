<x-datatable :collection="$employedTagTeams">
    <thead>
        <th>Id</th>
        <th>Tag Team Name</th>
        <th>Date Employed</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($employedTagTeams as $tagTeam)
            <tr>
                <td>{{ $tagTeam->id }}</td>
                <td>{{ $tagTeam->name }}</td>
                <td>{{ $tagTeam->employed_at->toDateString() }}</td>
                <td>{{ $tagTeam->status->label() }}</td>
                <td>
                    {{-- @include('tagTeams.partials.action-cell', [
                        'tagTeam' => $tagTeam,
                        'actions' => collect([
                            'retire', 'employ', 'release', 'suspend', 'reinstate', 'injure', 'clearInjury'
                        ])
                    ]) --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>

