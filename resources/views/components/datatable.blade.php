<table data-table="{{ $dataTable }}" {{ $attributes->merge(['class' => 'table table-hover table-bordered']) }}>
    {{ $slot }}
</table>
