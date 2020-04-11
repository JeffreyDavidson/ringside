<div class="dropdown">
    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
        <i class="flaticon-more-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            @can('view', $model)
                <x-buttons.view :route="route('wrestlers.show', $model)" />
            @endcan

            @can('update', $model)
                <x-buttons.edit :route="route('wrestlers.edit', $model)" />
            @endcan

            @can('delete', $model)
                <x-buttons.delete :route="route('wrestlers.destroy', $model)" />
            @endcan

            @if($model->canBeRetired())
                @can('retire', $model)
                    <x-buttons.retire :route="route('wrestlers.retire', $model)" />
                @endcan
            @endif

            @if($model->canBeUnretired())
                @can('unretire', $model)
                    <x-buttons.unretire :route="route('wrestlers.unretire', $model)" />
                @endcan
            @endif

            @if($model->canBeEmployed())
                @can('employ', $model)
                    <x-buttons.employ :route="route('wrestlers.employ', $model)" />
                @endcan
            @endif

            @if($model->canBeReleased())
                @can('release', $model)
                    <x-buttons.release :route="route('wrestlers.release', $model)" />
                @endcan
            @endif

            @if($model->canBeSuspended())
                @can('suspend', $model)
                    <x-buttons.suspend :route="route('wrestlers.suspend', $model)" />
                @endcan
            @endif

            @if($model->canBeReinstated())
                @can('reinstate', $model)
                    <x-buttons.reinstate :route="route('wrestlers.reinstate', $model)" />
                @endcan
            @endif

            @if($model->canBeInjured())
                @can('injure', $model)
                    <x-buttons.injure :route="route('wrestlers.injure', $model)" />
                @endcan
            @endif

            @if($model->canBeClearedFromInjury())
                @can('clearFromInjury', $model)
                    <x-buttons.recover :route="route('wrestlers.clear-from-injury', $model)" />
                @endcan
            @endif
        </ul>
    </div>
</div>
