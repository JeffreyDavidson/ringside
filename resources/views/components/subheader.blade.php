<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">{{ $title }}</h3>
        @if($displayRecordsCount || $search || $filters)
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        @endif
        @if($displayRecordsCount)
            <livewire:data-tables-total>
        @endif
        @if($search)
            <x-search />
        @endif
        @if($filters)
            @include($filters)
        @endif
    </div>
    @isset($actions)
        <div class="kt-subheader__toolbar">
            {{ $actions }}
        </div>
    @endisset
</div>
