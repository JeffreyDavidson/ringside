@aware(['component', 'tableName', 'isTailwind', 'isBootstrap', 'isBootstrap4', 'isBootstrap5'])
Show
<select wire:model.live="perPage" id="{{ $tableName }}-perPage"
    {{ $attributes->merge($component->getPerPageFieldAttributes())->class(['select select-sm w-16'])->except(['default', 'default-styling', 'default-colors']) }}>
    @foreach ($component->getPerPageAccepted() as $item)
        <option value="{{ $item }}" wire:key="{{ $tableName }}-per-page-{{ $item }}">
            {{ $item === -1 ? __('All') : $item }}
        </option>
    @endforeach
</select>
per page
