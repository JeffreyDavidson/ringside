@props([
    'icon' => '',
    'label' => '',
])

<li class="flex flex-col p-0 m-0" @click="open = false">
    {{ $slot }}
</li>
