<div {{ $attributes->merge(['class' => 'flex grow shrink-0 py-5 pr-2']) }}>
    <div class="scrollable-y-hover grow shrink-0 flex pl-2 lg:pl-5 pr-1 lg:pr-3">
        {{ $slot }}
    </div>
</div>
