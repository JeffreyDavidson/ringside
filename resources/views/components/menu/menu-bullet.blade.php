<span @class([
    'menu-bullet flex w-[6px] relative before:absolute before:top-0 before:size-[6px] before:rounded-full before:-translate-x-1/2 before:-translate-y-1/2',
    'before:bg-primary' => request()->url() === $attributes['href'],
])></span>
