<button {{ $attributes->merge([
    'class' => 'inline-flex items-center cursor-pointer leading-4 rounded-md h-10 px-4 gap-1.5 border border-transparent border-solid text-white bg-blue-500 hover:bg-primary-active hover:shadow-primary'
]) }}>
    {{ $slot }}
</button>
