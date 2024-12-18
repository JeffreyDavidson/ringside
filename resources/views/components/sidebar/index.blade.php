<div class="bg-light border-e border-e-gray-200 z-20 hidden flex-col items-stretch shrink-0 x-transition.duration.3000ms group lg:flex" :class="!sidebarIsOpen ? 'lg:w-[80px]' : 'lg:w-[280px]'" :class="sidebarIsOpen ? 'w-[280px]' : 'w-[80px]'" x-on:mouseover="sidebarIsOpen = true" x-on:mouseout="sidebarIsOpen = false" {{ $attributes }}">
    <x-sidebar.header/>
    <x-sidebar.content/>
</div>
