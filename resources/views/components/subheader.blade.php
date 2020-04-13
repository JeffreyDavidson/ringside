<div class="kt-subheader kt-grid__item" id="kt_subheader">
    <div class="kt-subheader__main">
        <h3 class="kt-subheader__title">{{ $title }}</h3>
        @if($search || $filters)
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
        @endif
        @if($search)
            <x-search />
        @endif
        @if($filters)
            @include($filters)
        @endif
    </div>
    <div class="kt-subheader__toolbar">
        <a href="{{ $link }}" class="btn btn-label-brand btn-bold">
            {{ $linkText }}
        </a>
    </div>
</div>
