<div class="flex" x-data="{ open: false, toggle() { this.open = !this.open } }" x-on:click.outside="open = false">
    <div class="flex flex-col">
        <button x-ref="button" x-on:click="toggle()"
            class="inline-flex align-center cursor-pointer leading-4 h-10 font-medium border-1 border-solid border-transparent justify-center shrink-0 p-0 gap-0 w-10 rounded-full grow">
            <img alt="" class="size-9 rounded-full border-2 border-success shrink-0"
                src="{{ Vite::image('avatars/300-2.png') }}">
            </img>
        </button>
        <div x-show="open"
            class="fixed z-[105px] m-0 border border-gray-300 dark:border-gray-200 w-screen max-w-[250px] bottom-auto left-auto top-0 right-0 [transform:translate3d(-350px,65px,0)] bg-[--tw-dropdown-background-color] dark:shadow-[--tw-dropdown-box-shadow] rounded-xl will-change-transform py-2.5">
            <div class="flex items-center justify-between px-5 py-1.5 gap-1.5">
                <div class="flex items-center gap-2">
                    <img alt="" class="size-9 rounded-full border-2 border-success"
                        src="{{ Vite::image('avatars/300-2.png') }}">
                    <div class="flex flex-col gap-1.5">
                        <span class="text-sm text-gray-800 font-semibold leading-none">
                            {{ auth()->user()->first_name }} {{ auth()->user()->last_name }}
                        </span>
                        <span class="text-xs text-gray-600 font-medium leading-none">
                            {{ auth()->user()->email }}
                        </span>
                    </div>
                    </img>
                </div>
            </div>
            <div class="my-2.5 border-b dark:[--tw-dropdown-border]"></div>
            <div class="flex flex-col">
                <div class="flex flex-col p-0 m-0 mb-0.5">
                    <div class="flex items-center grow mx-2.5 p-2.5 rounded-md cursor-pointer group hover:bg-gray-100 dark:hover:bg-coal-300">
                        <span class="flex items-center shrink-0 mr-2.5 group-hover:text-primary">
                            <i class="ki-filled ki-icon"></i>
                        </span>
                        <span class="flex items-center grow text-gray-800 text-2sm font-medium group-hover:text-gray-900">Language</span>
                        <div class="flex items-center gap-1.5 rounded-md border border-gray-300 text-gray-600 p-1.5 text-2xs font-medium shrink-0">
                            English
                            <img alt="" class="inline-block size-3.5 rounded-full" src="{{ Vite::image('flags/united-states.svg') }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-2.5 border-b dark:[--tw-dropdown-border]"></div>
            <div class="flex flex-col">
                <div class="flex flex-col p-0 m-0 mb-0.5">
                    <div class="flex items-center grow mx-2.5 p-2.5 rounded-md cursor-pointer group hover:bg-gray-100 dark:hover:bg-coal-300">
                        <span class="flex items-center shrink-0 mr-2.5 group-hover:text-primary">
                            <i class="ki-filled ki-moon"></i>
                        </span>
                        <span
                            class="flex items-center grow text-gray-800 text-2sm font-medium group-hover:text-gray-900">
                            Dark Mode
                        </span>
                        <label class="flex items-center cursor-pointer gap-2.5">
                            <x-topbar.dark />
                        </label>
                    </div>
                </div>
                <div class="flex flex-col m-0 px-4 py-1.5">
                    <a class="btn btn-sm btn-light justify-center"
                        href="html/demo1/authentication/classic/sign-in.html">
                        Log out
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
