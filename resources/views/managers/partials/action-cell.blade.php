<div class="dropdown">
    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
        <i class="flaticon-more-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            @can('view', $model)
                @viewbutton(['route' => route('managers.show', $model)])
                @endviewbutton
            @endcan

            @can('update', $model)
                @editbutton(['route' => route('managers.edit', $model)])
                @endeditbutton
            @endcan

            @can('delete', $model)
                @deletebutton(['route' => route('managers.destroy', $model)])
                @enddeletebutton
            @endcan

            @can('retire', $model)
                @if($model->canBeRetired())
                    @retirebutton(['route' => route('managers.retire', $model)])
                    @endretirebutton
                @endif
            @endcan

            @can('unretire', $model)
                @if($model->canBeUnretired())
                    <unretire :route="route('managers.unretire', $model)" />
                @endif
            @endcan

            @can('employ', $model)
                @if($model->canBeEmployed())
                    @employbutton(['route' => route('managers.employ', $model)])
                    @endemploybutton
                @endif
            @endcan

            @can('suspend', $model)
                @if($model->canBeSuspended())
                    @suspendbutton(['route' => route('managers.suspend', $model)])
                    @endsuspendbutton
                @endif
            @endcan

            @can('reinstate', $model)
                @if($model->canBeReinstated())
                    @reinstatebutton(['route' => route('managers.reinstate', $model)])
                    @endreinstatebutton
                @endif
            @endcan

            @can('injure', $model)
                @if($model->canBeInjured())
                    @injurebutton(['route' => route('managers.injure', $model)])
                    @endinjurebutton
                @endif
            @endcan

            @can('recover', $model)
                @if($model->canBeMarkedAsHealed())
                    @recoverbutton(['route' => route('managers.recover', $model)])
                    @endrecoverbutton
                @endif
            @endcan
        </ul>
    </div>
</div>
