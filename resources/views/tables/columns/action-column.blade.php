<div class="p-4 inline-flex " x-data="{ open: false, toggle() { this.open = !this.open }  }" @click.outside="open = false">
    <button x-ref="button" @click="toggle()" class="w-4 text-white rounded-md bg-black">&vellip;</button>
    <div x-show.important="open" x-anchor.bottom="$refs.button" class="text-white bg-black rounded-md w-full max-w-[175px]">
        <ul class="w-full ">
            <li class="hover:bg-gray-500 hover:text-black px-4" @click="open = false">Item 1</li>
            <li class="hover:bg-gray-500 hover:text-black px-4" @click="open = false">Item 2</li>
            <li class="hover:bg-gray-500 hover:text-black px-4" @click="open = false">Item 3</li>
            <li class="hover:bg-gray-500 hover:text-black px-4" @click="open = false">Item 4</li>
        </ul>
    </div>
</div>
