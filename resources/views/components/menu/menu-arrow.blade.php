<span
    {{ $attributes->merge(['class' => 'flex items-center text-gray-400 w-5 shrink-0 justify-end ml-1 mr-[-10px]']) }}>
    <i x-show="!open" class="ki-filled ki-plus text-2xs menu-item-show:hidden"></i>
    <i x-show="open" class="ki-filled ki-minus text-2xs menu-item-show:inline-flex"></i>
</span>
