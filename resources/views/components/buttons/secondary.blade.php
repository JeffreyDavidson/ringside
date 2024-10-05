<button {{ $attributes->merge([
    'class' => 'inline-flex items-center cursor-pointer leading-4 rounded-md h-10 px-4 gap-1.5 border hover:text-gray-800 hover:border-gray-300 hover:bg-light-active hover:shadow-default',
]) }}>
    {{ $slot }}
</button>
