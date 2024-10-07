<div x-data="{ switchOn: false }" class="flex items-center justify-center space-x-2 px-2">
    <input id="toggleDarkSwitch" type="checkbox" name="toggleDarkSwitch" class="hidden" :checked="switchOn">
    <button id="toggleDarkModeButton" x-ref="darkModeToggleButton" type="button" @click="darkMode = ! darkMode"
        :class="darkMode ? 'bg-blue-600' : 'bg-neutral-200'"
        class="relative inline-flex h-6 py-0.5 ml-4 focus:outline-none rounded-full w-10" x-cloak><span
            :class="darkMode ? 'translate-x-[18px]' : 'translate-x-0.5'"
            class="w-5 h-5 duration-200 ease-in-out bg-white rounded-full shadow-md"></span></button>
    <label for="toggleDarkModeButton" @click="$refs.darkModeToggleButton.click(); $refs.darkModeToggleButton.focus()"
        :id="$id('toggleDarkSwitch')" :class="{ 'text-blue-600': darkMode, 'text-gray-400': !darkMode }"
        class="text-sm select-none" x-cloak></label>
</div>
