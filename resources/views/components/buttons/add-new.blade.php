@props([
    'component' => '',
])

<button class="inline-flex items-center cursor-pointer rounded-md border border-solid border-transparent outline-none h-8 px-3 font-medium text-xs text-white gap-[.275rem] dark:bg-primary dark:hover:bg-primary-active hover:shadow-none" @click="$dispatch('openModal', { component: {{ $component }} })">
    {{ $slot }}
</button>
