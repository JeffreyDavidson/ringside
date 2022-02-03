<x-actions-dropdown>
    <div class="py-4 menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px show" data-kt-menu="true" style="z-index: 105; position: fixed; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-374px, 357px, 0px);" data-popper-placement="bottom-end">
        @can('update', $title)
            <x-buttons.edit :route="route('titles.edit', $title)" />
        @endcan

        @can('delete', $title)
            <x-buttons.delete wire:click="delete($title)" />
        @endcan

        @if ($actions->contains('retire'))
            @if ($title->canBeRetired())
                @can('retire', $title)
                    <x-buttons.retire :route="route('titles.retire', $title)" />
                @endcan
            @endif
        @endif

        @if ($actions->contains('unretire'))
            @if ($title->canBeUnretired())
                @can('unretire', $title)
                    <x-buttons.unretire :route="route('titles.unretire', $title)" />
                @endcan
            @endif
        @endif

        @if ($actions->contains('activate'))
            @if ($title->canBeActivated())
                @can('activate', $title)
                    <x-buttons.activate :route="route('titles.activate', $title)" />
                @endcan
            @endif
        @endif

        @if ($actions->contains('deactivate'))
            @if ($title->canBeDeactivated())
                @can('deactivate', $title)
                    <x-buttons.deactivate :route="route('titles.deactivate', $title)" />
                @endcan
            @endif
        @endif
    </div>
</x-actions-dropdown>
