@props(['buttonLabel' => 'Open', 'index' => 0])
<x-testmenu.main-item>
    <div x-data="{ open: false }" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 scale-90"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-90" @click.outside="open = false">
        <button x-ref="subButton{{ $index }}" x-on:click="open = true">{{ $buttonLabel }}</button>
        <div x-show="open" x-anchor.right-start="$refs.subButton{{ $index }}">
            <ul>
                {{ $slot }}
            </ul>
        </div>
    </div>
</x-testmenu.main-item>
