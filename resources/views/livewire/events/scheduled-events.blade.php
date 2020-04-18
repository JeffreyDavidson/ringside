<x-datatable :collection="$scheduledEvents">
    <thead>
        <th>Id</th>
        <th>Event Name</th>
        <th>Date</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($scheduledEvents as $event)
            <tr>
                <td>{{ $event->id }}</td>
                <td>{{ $event->name }}</td>
                <td>{{ $event->date->toDateString() }}</td>
                <td>{{ $event->status->label() }}</td>
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

