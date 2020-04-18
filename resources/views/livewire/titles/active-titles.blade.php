<x-datatable :collection="$activeTitles">
    <thead>
        <th>Id</th>
        <th>Title Name</th>
        <th>Date Introduced</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($activeTitles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->activated_at->toDateString() }}</td>
                <td>{{ $title->status->label() }}</td>
                <td>
                    {{-- @include('titles.partials.action-cell', [
                        'title' => $title,
                        'actions' => collect([

                        ])
                    ]) --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>

