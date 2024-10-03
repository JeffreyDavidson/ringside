<div class="p-4 inline-flex " x-data="{ open: false, toggle() { this.open = !this.open } }" @click.outside="open = false">
    <button x-ref="button" @click="toggle()" class="w-4 text-white rounded-md bg-black">&vellip;</button>
    <div x-show.important="open" x-anchor.bottom="$refs.button"
        class="menu-default text-white bg-black rounded-md w-full max-w-[175px]">
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
            <div class="menu-separator"></div>
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
            <div class="menu-separator"></div>
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
