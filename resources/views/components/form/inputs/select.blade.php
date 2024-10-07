@props([
    'options' => [],
    'select_type' => 'an',
    'select_name' => 'Option'
])

<select
    {{ $attributes->merge(['class' => 'block w-full appearance-none shadow-none font-medium text-2sm leading-4 border border-1 border-gray-300 rounded-md h-10 px-3 text-gray-700 bg-light hover:border-gray-400']) }}>
    <option value="">{{ __('core.select_'.$select_type, ['name' => $select_name])}}</option>
    @forelse($options as $key => $value)
        <option value="{{ $key }}">{{ $value }}</option>
    @empty
      <option>{{ __('core.select.no-options') }}</option>
    @endforelse
</select>
