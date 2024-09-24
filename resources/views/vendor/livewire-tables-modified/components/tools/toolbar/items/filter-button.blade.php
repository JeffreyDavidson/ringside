@aware(['component', 'tableName','isTailwind','isBootstrap','isBootstrap4','isBootstrap5'])
@props([])

<button
    type="button"
    @class([
        'btn btn-sm btn-outline btn-primary',
    ])
>
    <i class="ki-filled ki-setting-4"></i>
    @lang('Filters')
</button>
