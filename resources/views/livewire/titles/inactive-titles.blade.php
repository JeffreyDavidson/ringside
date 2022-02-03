<div class="card">
    @include('livewire.venues.partials.header')
    <div class="py-4 card-body">
        <x-data-table :collection="$inactiveTitles">
            <thead>
                <th>Id</th>
                <th>Title Name</th>
                <th>Date Deactivated</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @forelse ($inactiveTitles as $title)
                    <tr>
                        <td>{{ $title->id }}</td>
                        <td>{{ $title->name }}</td>
                        <td>{{ $title->->toDateString() }}</td>
                        <td>
                            <x-actions-dropdown>
                                <x-buttons.view :route="route('titles.show', $title)" />
                                <x-buttons.edit :route="route('titles.edit', $title)" />
                                <x-buttons.delete :route="route('titles.destroy', $title)" />
                                <x-buttons.activate :route="route('titles.activate', $title)" />
                            </x-actions-dropdown>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No matching records found</td>
                    </tr>
                @endforelse
            </tbody>
        </x-data-table>
    </div>
</div>
