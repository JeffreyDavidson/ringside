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
                    {{-- @include('titles.partials.action-cell', [
                        'title' => $title,
                        'actions' => collect([
                            // 'deactivate' // 33 20
                            // 'deactivate', 'retire' // 53 20
                            // 'retire' // 23 20
                        ])
                    ]) --}}
                    <x-actions-dropdown>
                        <x-buttons.view :route="route('titles.show', $title)" />
                        <x-buttons.edit :route="route('titles.edit', $title)" />
                        <x-buttons.delete :route="route('titles.destroy', $title)" />
                        <x-buttons.retire :route="route('titles.retire', $title)" />
                        <x-buttons.deactivate :route="route('titles.deactivate', $title)" />
                    </x-actions-dropdown>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No matching records found</td></tr>
        @endforelse
    </tbody>
</x-datatable>

