@props(['buttonLabel' => 'Open', 'index' => 0])

<div x-data="{ open: false }" @click.outside="open = false">
    <button x-ref="menuButton{{ $index }}" x-on:click="open = !open">{{ $buttonLabel }}</button>
    <div x-show="open" x-anchor.bottom="$refs.menuButton{{ $index }}"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90"
>
        <ul>
            {{ $slot }}
        </ul>
    </div>
</div>
