<div class="bg-light border-e border-e-gray-200 z-20 hidden flex-col items-stretch shrink-0 hover:transition hover:ease hover:duration-300 hover:w-[280px] group lg:flex" :class="!sidebarIsOpen ? 'lg:w-[80px]' : 'lg:w-[280px]'" {{ $attributes }}">
    <x-sidebar.header/>
    <x-sidebar.content/>
</div>
