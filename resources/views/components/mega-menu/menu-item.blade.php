<div @mouseover="open = true" x-data="{
    open: true,
    toggle() {
        this.open = this.open ? this.close() : true
    },
    close() {
        this.open = false
    }
}" @class(['flex flex-col p-0 m-0 group'])>
    <div @mouseleave="open = false"
        class="menu-link text-sm text-gray-800 menu-link-hover:text-primary menu-item-active:text-gray-900 menu-item-show:text-primary menu-item-here:text-gray-900 menu-item-active:font-medium menu-item-here:font-medium">
        {{ $slot }}
    </div>

    @if (isset($subMenu))
        <div :class="open ? 'show' : ''" x-show="open" class="menu-dropdown w-full gap-0 lg:max-w-[875px]">
            <div class="pt-4 pb-2 lg:p-7.5">
                <div class="grid lg:grid-cols-2 gap-5 lg:gap-10">
                    <div class="menu menu-default menu-fit flex-col">
                        <h3 class="text-sm text-gray-800 font-semibold leading-none pl-2.5 mb-2 lg:mb-5">
                            Roster
                        </h3>
                        {{ $subMenu }}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
