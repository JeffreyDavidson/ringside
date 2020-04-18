<x-datatable :collection="$activeTitles">
    <thead>
        <th>Id</th>
        <th>Title Name</th>
        <th>Date Introduced</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($activeTitles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->activated_at->toDateString() }}</td>
                <td>{{ $title->status->label() }}</td>
                <td>
                    @include('titles.partials.action-cell', [
                        'title' => $title,
                        'actions' => collect([
                            'deactivate', 'retire'
                        ])
                    ])
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No matching records found</td></tr>
        @endforelse
    </tbody>
</x-datatable>

