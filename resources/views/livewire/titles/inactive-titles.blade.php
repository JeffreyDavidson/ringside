<x-datatable :collection="$inactiveTitles">
    <thead>
        <th>Id</th>
        <th>Title Name</th>
        <th>Date Deactivated</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($inactiveTitles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->deactivated_at->toDateString() }}</td>
                <td>
                    @include('titles.partials.action-cell', [
                        'title' => $title,
                        'actions' => collect([
                            'activate'
                        ])
                    ])
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No matching records found</td>
            </tr>
        @endforelse
    </tbody>
</x-datatable>
