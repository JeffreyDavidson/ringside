<textarea
    {{ $attributes->merge(['class' => 'block w-full appearance-none shadow-none text-2sm bg-light-active rounded-md py-[.55rem] px-3 border border-solid border-gray-300 text-gray-700 hover:border-gray-400']) }}
>
@isset($value){{ $value }}@endisset
</textarea>
