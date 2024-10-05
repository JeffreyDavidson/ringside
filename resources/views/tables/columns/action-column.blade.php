<div class="inline-flex" x-data="{ open: false, toggle() { this.open = !this.open } }" @click.outside="open = false">
    <button x-ref="button" @click="toggle()" class="inline-flex items-center cursor-pointer rounded-md leading-none border border-transparent border-solid border-1 h-8 w-8 font-medium text-xs justify-center p-0 gap-0 shrink-0">
        <i class="ki-solid ki-dots-vertical"></i>
    </button>
    <div x-cloak x-show.important="open" x-anchor.bottom="$refs.button"
        class="py-2.5 w-full bg-black rounded-md max-w-[175px] border border-solid border-gray-200 dark:border-gray-100 gap-0.5">
        <ul>
            @if ($links['view'] ?? true)
                <x-dropdown.item>
                    <x-dropdown.item.link href="{{ route($path . '.show', $rowId) }}" icon="ki-search-list" label="View" />
                </x-dropdown.item>
            @endif
            <x-dropdown.item-separator/>
            @if ($links['edit'] ?? true)
                <x-dropdown.item>
                    <x-dropdown.item.button wire:click="$dispatch('openModal', { component: 'wrestlers.modals.form-modal', arguments: { wrestler: {{ $rowId }} }})" icon="ki-pencil" label="Edit"/>
                </x-dropdown.item>
            @endif
            <x-dropdown.item-separator/>
            @if ($links['delete'] ?? true)
                <x-dropdown.item>
                    <x-dropdown.item.button wire:click="$dispatch('openModal', { component: 'wrestlers.delete-wreslter', arguments: { wrestler: {{ $rowId }} }})" icon="ki-trash" label="Remove"/>
                </x-dropdown.item>
            @endif
        </ul>
    </div>
</div>
