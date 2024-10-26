@props(['icon' => null])
<li {{ $attributes->merge()->class(['whitespace-nowrap px-2']) }}>
    @isset($icon)
        <i class="ki-filled {{ $icon }} text-lg"></i>
    @endisset
    {{ $slot }}
</li>
