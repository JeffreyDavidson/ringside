<x-datatable>
    <x-slot name="head">
        <x-table.heading
            sortable
            multi-column
            wire:click="sortBy('name')"
            :direction="$sorts['name'] ?? null"
            class="min-w-125px sorting">Title Name</x-table.heading>
        <x-table.heading class="min-w-125px sorting_disabled">Previous Champion</x-table.heading>
        <x-table.heading
            sortable
            multi-column
            wire:click="sortBy('days_held')"
            :direction="$sorts['days_held'] ?? null"
            class="min-w-70px sorting">Days Held</x-table.heading>
        <x-table.heading
            sortable
            multi-column
            wire:click="sortBy('won_at')"
            :direction="$sorts['won_at'] ?? null"
            class="min-w-70px sorting">Dates Held</x-table.heading>
    </x-slot>
    <x-slot name="body">
        @forelse ($titlesChampionships as $titleChampionship)
{{--            @dump($titleChampionship->title->previousChampionship)--}}
            <x-table.row :class="$loop->odd ? 'odd' : 'even'" wire:key="row-{{ $titleChampionship->title_id }}">
                <x-table.cell>
                    <x-route-link
                        :route="route('titles.show', $titleChampionship->title)"
                        label="{{ $titleChampionship->title->name }}"
                    />
                </x-table.cell>
                <x-table.cell>{{ $titleChampionship->title->previousChampionship->name ?? "First Champion" }}</x-table.cell>
                <x-table.cell>{{ $titleChampionship->days_held_count }}</x-table.cell>
                <x-table.cell>
                    {{ $titleChampionship->won_at?->toDateString() ?? "no won at" }}
                        -
                    {{ $titleChampionship->lost_at?->toDateString() ?? "Present" }}
                </x-table.cell>
            </x-table.row>
        @empty
            <x-table.row-no-data colspan="3"/>
        @endforelse
    </x-slot>
    <x-slot name="footer">
        <x-table.footer :collection="$titlesChampionships" />
    </x-slot>
</x-datatable>
