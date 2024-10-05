<div class="inline-flex" x-data="{ open: false, toggle() { this.open = !this.open } }" @click.outside="open = false">
    <button x-ref="button" @click="toggle()" class="inline-flex items-center cursor-pointer rounded-md leading-none border border-transparent border-solid border-1 h-8 w-8 font-medium text-xs justify-center p-0 gap-0 shrink-0">
        <i class="ki-solid ki-dots-vertical"></i>
    </button>
    <div x-cloak x-show.important="open" x-anchor.bottom="$refs.button"
        class="menu-default py-2.5 text-white bg-black rounded-md max-w-[175px]">
        <ul class="w-full">
            @if ($links['view'] ?? true)
                <li class="menu-item" @click="open = false">
                    <a class="menu-link" href="{{ route($path . '.show', $rowId) }}">
                        <span class="menu-icon">
                            <i class="ki-filled ki-search-list"></i>
                        </span>
                        <span class="menu-title">View</span>
                    </a>
                </li>
            @endif
            <div class="my-2.5 border-t-2 border-solid border-black-blue"></div>
            @if ($links['edit'] ?? true)
                <li class="menu-item" @click="open = false">
                    <button class="menu-link"
                        wire:click="$dispatch('openModal', { component: 'wrestlers.wrestler-modal', arguments: { wrestler: {{ $rowId }} }})">
                        <span class="menu-icon">
                            <i class="ki-filled ki-pencil"></i>
                        </span>
                        <span class="menu-title">Edit</span>
                    </button>
                </li>
            @endif
            <div class="my-2.5 border-t-2 border-solid border-black-blue"></div>
            @if ($links['delete'] ?? true)
                <li class="menu-item" @click="open = false">
                    <button class="menu-link"
                        wire:click="$dispatch('openModal', { component: 'wrestlers.delete-wrestler', arguments: { wrestler: {{ $rowId }} }})">
                        <span class="menu-icon">
                            <i class="ki-filled ki-trash"></i>
                        </span>
                        <span class="menu-title">Remove</span>
                    </button>
                </li>
            @endif
        </ul>
    </div>
</div>
