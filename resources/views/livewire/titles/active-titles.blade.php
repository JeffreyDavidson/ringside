<div class="card">
    @include('livewire.titles.partials.header')
    <div class="py-4 card-body">
        <x-data-table :collection="$activeTitles">
            <thead>
                <tr class="text-start text-muted fw-bolder fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2 sorting_disabled" rowspan="1" colspan="1" style="width: 29.25px;" aria-label="">
                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                            <input class="form-check-input" type="checkbox" wire:model="selectPage">
                        </div>
                    </th>
                    <th>Title Name</th>
                    <th>Date Introduced</th>
                    <th class="text-end min-w-100px sorting_disabled">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($activeTitles as $title)
                    <tr>
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input" type="checkbox" value="{{ $title->id }}" wire:model="selected">
                            </div>
                        </td>
                        <td>
                            <a class="mb-1 text-gray-800 text-hover-primary" href="{{ route('titles.show', $title) }}">
                                {{ $title->name }}
                            </a>
                        </td>
                        <td>{{ $title->activatedAt->toDateString() }}</td>
                        <td class="text-end">
                            @include('titles.partials.action-cell', [
                                'title' => $title,
                                'actions' => collect('retire', 'deactivate')
                            ])
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4">No matching records found</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </div>
</div>
