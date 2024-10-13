@props([
    'enum' => ''
])

@foreach ($enum::cases() as $case)
    <x-tables.header.metadata-label>{{ $case->name }}</x-tables.header.metadata-label>
    <x-tables.header.metadata-count>
        {{ $this->builder()->where('status', $case->name)->count() }}
    </x-tables.header.metadata-count>
@endforeach
