<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">{{ $title }}</h3>
        @if($search || $filters)
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        @endif
        @isset($search)
            {{ $search }}
        @endif
        @if($filters)
            {{ $filters }}
        @endif
    </div>
    @isset($actions)
        <div class="kt-subheader__toolbar">
            {{ $actions }}
        </div>
    @endisset
</div>
