<x-datatable :collection="$inactiveTitles">
    <thead>
        <th>Id</th>
        <th>Title Name</th>
        <th>Date Deactivated</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($inactiveTitles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>{{ $title->deactivated_at->toDateString() }}</td>
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
