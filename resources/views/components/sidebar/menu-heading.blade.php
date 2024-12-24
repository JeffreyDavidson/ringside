<div class="flex flex-col m-0 p-0 pt-2.25 pb-px">
    <span class="uppercase text-2sm font-medium text-gray-500 ps-[10px] pe-[10px]"
        :class="!sidebarIsOpen ? `lg:relative lg:invisible lg:before:content-['...'] lg:before:ms-[.225px] lg:before:text-current lg:before:visible lg:before:inline-block lg:before:bottom-2/4 lg:before:start-0 lg:before:translate-x-full lg:before:absolute` : ''">
        {{ $slot }}
    </span>
</div>
