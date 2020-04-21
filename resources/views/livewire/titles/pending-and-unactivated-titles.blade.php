<x-datatable :collection="$pendingAndUnactivatedTitles">
    <thead>
        <th>Id</th>
        <th>Title Name</th>
        <th>Date Introduced</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @forelse($pendingAndUnactivatedTitles as $title)
            <tr>
                <td>{{ $title->id }}</td>
                <td>{{ $title->name }}</td>
                <td>
                    @if($title->hasFutureActivation())
                        {{ $title->activated_at->toDateString() }}
                    @else
                        TBD
                    @endif
                </td>
                <td>
                    {{-- @include('titles.partials.action-cell', [
                        'title' => $title,
                        'actions' => collect([
                            'activate'
                        ])
                    ]) --}}
                    <x-actions-dropdown>
                        <x-buttons.view :route="route('titles.show', $title)" />
                        <x-buttons.edit :route="route('titles.edit', $title)" />
                        <x-buttons.delete :route="route('titles.destroy', $title)" />
                        <x-buttons.activate :route="route('titles.activate', $title)" />
                    </x-actions-dropdown>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No matching records found</td></tr>
        @endforelse
    </tbody>
</x-datatable>

