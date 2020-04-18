<x-datatable :collection="$retiredTitles">
    <thead>
        <th>Id</th>
        <th>Title Name</th>
        <th>Date Retired</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($retiredTitles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->retired_at->toDateString() }}</td>
                <td>
                    {{-- @include('titles.partials.action-cell', [
                        'title' => $title,
                        'actions' => collect([
                            'unretire'
                        ])
                    ]) --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
