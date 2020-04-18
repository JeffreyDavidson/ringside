<x-datatable :collection="$pastEvents">
    <thead>
        <th>Id</th>
        <th>Event Name</th>
        <th>Date</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($pastEvents as $event)
            <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->name }}</td>
                <td>{{ $event->toDateString() }}</td>
                <td>
                    {{-- @include('events.partials.action-cell', [
                        'event' => $event,
                        'actions' => collect([
                        ])
                    ]) --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>
