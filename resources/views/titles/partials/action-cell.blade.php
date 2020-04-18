<div class="dropdown">
    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
        <i class="flaticon-more-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            @can('view', $title)
                <x-buttons.view :route="route('titles.show', $title)" />
            @endcan

            @can('update', $title)
                <x-buttons.edit :route="route('titles.edit', $title)" />
            @endcan

            @can('delete', $title)
                <x-buttons.delete :route="route('titles.destroy', $title)" />
            @endcan

            @if ($actions->contains('retire'))
                @if($title->canBeRetired())
                    @can('retire', $title)
                        <x-buttons.retire :route="route('titles.retire', $title)" />
                    @endcan
                @endif
            @endif

            @if ($actions->contains('unretire'))
                @if($title->canBeUnretired())
                    @can('unretire', $title)
                        <x-buttons.unretire :route="route('titles.unretire', $title)" />
                    @endcan
                @endif
            @endif

            @if ($actions->contains('activate'))
                @if($title->canBeActivated())
                    @can('activate', $title)
                        <x-buttons.activate :route="route('titles.activate', $title)" />
                    @endcan
                @endif
            @endif

            @if ($actions->contains('deactivate'))
                @if($title->canBeDeactivated())
                    @can('deactivate', $title)
                        <x-buttons.deactivate :route="route('titles.deactivate', $title)" />
                    @endcan
                @endif
            @endif
        </ul>
    </div>
</div>
