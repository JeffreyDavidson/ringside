<x-datatable :collection="$venues">
    <thead>
        <th>Id</th>
        <th>Venue Name</th>
        <th>Address</th>
        <th>City</th>
        <th>State</th>
        <th>Zip Code</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($venues as $venue)
            <tr>
                <td>{{ $venue->id }}</td>
                <td>{{ $venue->name }}</td>
                <td>{{ $venue->address1 }}</td>
                <td>{{ $venue->city }}</td>
                <td>{{ $venue->state }}</td>
                <td>{{ $venue->zip }}</td>
                <td>
                    {{-- @include('venues.partials.action-cell', [
                        'venue' => $venue,
                        'actions' => collect([

                        ])
                    ]) --}}
                </td>
            </tr>
        @endforeach
    </tbody>
</x-datatable>

