@aware(['component', 'isTailwind', 'isBootstrap'])
@props([
    'displayMinimisedOnReorder' => false,
    'hideUntilReorder' => false,
    'customAttributes' => ['default' => true],
])

<th x-cloak {{ $attributes }} scope="col" {{ $attributes->merge($customAttributes) }}
    @if ($hideUntilReorder) :class="!reorderDisplayColumn && 'w-0 p-0 hidden'" @endif>
    {{ $slot }}
</th>
