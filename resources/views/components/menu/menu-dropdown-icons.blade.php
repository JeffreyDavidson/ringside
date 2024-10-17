@props([
    'subMenu' => '',
])

<span class="flex items-center shrink-0 ml-1 mr-[-10px] w-[20px] justify-end text-gray-400">
    <i x-show="!open" class="ki-filled ki-plus text-2xs" :class="open ? 'hidden' : ''" @click="!$subMenu"></i>
    <i x-show="open" class="ki-filled ki-minus text-2xs" :class="open ? 'inline-flex' : ''"></i>
</span>
