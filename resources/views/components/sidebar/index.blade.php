<div class="bg-light border-e border-e-gray-200 z-20 hidden flex-col items-stretch shrink-0 x-transition.duration.300ms group lg:flex" :class="!sidebarIsOpen ? 'lg:w-20' : 'lg:w-72'" :class="sidebarIsOpen ? 'w-72' : 'w-20'" x-on:mouseover="sidebarIsOpen = true" x-on:mouseout="sidebarIsOpen = false" {{ $attributes }}">
    <x-sidebar.header/>
    <x-sidebar.content/>
</div>
