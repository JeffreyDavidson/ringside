<div class="dropdown">
    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
        <i class="flaticon-more-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            @can('view', $model)
                <x-buttons.view :route="route('managers.show', $model)" />
            @endcan

            @can('update', $model)
                <x-buttons.edit :route="route('managers.edit', $model)" />
            @endcan

            @can('delete', $model)
                <x-buttons.delete :route="route('managers.destroy', $model)" />
            @endcan

            @if($model->canBeRetired())
                @can('retire', $model)
                    <x-buttons.retire :route="route('managers.retire', $model)" />\
                @endcan
            @endif

            @if($model->canBeUnretired())
                @can('unretire', $model)
                    <x-buttons.unretire :route="route('managers.unretire', $model)" />
                @endcan
            @endif

            @can('employ', $model)
                @if($model->canBeEmployed())
                    <x-buttons.employ :route="route('managers.employ', $model)" />
                @endif
            @endcan

            @can('suspend', $model)
                @if($model->canBeSuspended())
                    <x-buttons.suspend :route="route('managers.suspend', $model)" />
                @endif
            @endcan

            @can('reinstate', $model)
                @if($model->canBeReinstated())
                    <x-buttons.reinstate :route="route('managers.reinstate', $model)" />
                @endif
            @endcan

            @can('injure', $model)
                @if($model->canBeInjured())
                    <x-buttons.injure :route="route('managers.injure', $model)" />
                @endif
            @endcan

            @can('recover', $model)
                @if($model->canBeClearedFromInjury())
                    <x-buttons.recover :route="route('managers.recover', $model)" />
                @endif
            @endcan
        </ul>
    </div>
</div>
