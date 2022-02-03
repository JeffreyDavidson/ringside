<x-menu-link>
    <form action="{{ $route }}" method="post">
        @csrf
        @method('PATCH')
        <button class="px-3 menu-link">Retire</button>
    </form>
    <a href="{{ $route }}" class="px-3 menu-link">Retire</a>
</x-menu-link>
