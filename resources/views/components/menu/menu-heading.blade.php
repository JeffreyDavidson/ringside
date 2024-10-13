<div class="flex flex-col pt-2.25 pb-px">
    <span class="uppercase text-2sm font-medium text-gray-500 pl-[10px] pr-[10px]" x-text="sidebarOpen ? `{{ $slot }}` : '...'"
        :class="sidebarOpen ? 'lg:invisible lg:relative' : ''"
    ">
    </span>
</div>
