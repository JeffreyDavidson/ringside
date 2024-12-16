<div class="h-[70px] hidden items-center relative justify-between px-3 shrink-0 lg:flex lg:px-6">
    <a href="{{ route('dashboard') }}">
        <img x-show="sidebarIsOpen" class="min-h-[22px] max-w-none" src="{{ Vite::image('app/default-logo.svg') }}" />
        <img x-show="!sidebarIsOpen" class="min-h-[22px] max-w-none" src="{{ Vite::image('app/mini-logo.svg') }}" />
    </a>
    <button
        @click.prevent="sidebarIsOpen = !sidebarIsOpen"
        class="inline-flex items-center cursor-pointer leading-none ps-4 pe-4 font-medium text-2sm outline-none justify-center p-0 gap-0 size-[30px] rounded-lg border border-gray-200 bg-light text-gray-500 hover:text-gray-700 toggle absolute left-full top-2/4 -translate-x-2/4 -translate-y-2/4">
        <i class="ki-filled active:rotate-180 transition-all duration-300" :class="sidebarIsOpen ? 'ki-black-left-line' : 'ki-black-right-line'"></i>
    </button>
</div>
