<input
    type="number"
    {{ $attributes->merge([
        'class' => 'block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light hover:border-gray-400',
        'min' => 0,
        'max' => '',
    ]) }}>
