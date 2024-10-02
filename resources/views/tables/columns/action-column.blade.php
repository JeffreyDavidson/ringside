<div class="menu" x-data="{ open: false, toggle() { this.open = !this.open } }" @click.outside="open = false">
    <div class="menu-item" class="relative">
        <button x-ref="button" @click="toggle()" class="menu-toggle btn btn-sm btn-icon btn-light btn-clear">
            <i class="ki-filled ki-dots-vertical"></i>
        </button>
        <div class="menu-dropdown menu-default w-full max-w-[175px]" x-show="open"
            x-anchor.bottom="$refs.button"
            style="z-index: 105; inset: 0px 0px auto auto; margin: 0px; transform: translate3d(-391px, 322px, 0px);">
            @if ($links['view'] ?? true)
                <div class="menu-item">
                    <a class="menu-link" href="{{ route($path . '.show', $rowId) }}">
                        <span class="menu-icon">
                            <i class="ki-filled ki-search-list"></i>
                        </span>
                        <span class="menu-title">View</span>
                    </a>
                </div>
            @endif

            <div class="menu-separator"></div>

            @if ($links['edit'] ?? true)
                <div class="menu-item">
                    <button class="menu-link"
                        wire:click="$dispatch('openModal', { component: 'wrestlers.wrestler-modal', arguments: { wrestler: {{ $rowId }} }})">
                        <span class="menu-icon">
                            <i class="ki-filled ki-pencil"></i>
                        </span>
                        <span class="menu-title">Edit</span>
                    </button>
                </div>
            @endif

            <div class="menu-separator"></div>

            @if ($links['delete'] ?? true)
                <div class="menu-item">
                    <button class="menu-link" wire:confirm="Are you sure you want to delete this wrestler?"
                        wire:click="delete({{ $rowId }})">
                        <span class="menu-icon">
                            <i class="ki-filled ki-trash"></i>
                        </span>
                        <span class="menu-title">Remove</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
