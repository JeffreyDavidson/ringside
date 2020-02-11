<div class="dropdown">
    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
        <i class="flaticon-more-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            @can('view', $model)
                @viewbutton(['route' => route('tag-teams.show', $model)])
                @endviewbutton
            @endcan
            @can('update', $model)
                @editbutton(['route' => route('tag-teams.edit', $model)])
                @endeditbutton
            @endcan
            @can('delete', $model)
                @deletebutton(['route' => route('tag-teams.destroy', $model)])
                @enddeletebutton
            @endcan
            @can('retire', $model)
                @retirebutton(['route' => route('tag-teams.retire', $model)])
                @endretirebutton
            @endcan
            @can('unretire', $model)
                @unretirebutton(['route' => route('tag-teams.unretire', $model)])
                @endunretirebutton
            @endcan
            @can('employ', $model)
                @employbutton(['route' => route('tag-teams.employ', $model)])
                @endemploybutton
            @endcan
            @can('suspend', $model)
                @suspendbutton(['route' => route('tag-teams.suspend', $model)])
                @endsuspendbutton
            @endcan
            @can('reinstate', $model)
                @reinstatebutton(['route' => route('tag-teams.reinstate', $model)])
                @endreinstatebutton
            @endcan
        </ul>
    </div>
</div>
