<div class="dropdown">
    <a href="javascript:;" class="btn btn-sm btn-clean btn-icon btn-icon-md" data-toggle="dropdown">
        <i class="flaticon-more-1"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-right">
        <ul class="kt-nav">
            @can('view', $model)
                <li class="kt-nav__item">
                    <a href="{{ route('titles.show', $model) }}" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon2-expand"></i>
                        <span class="kt-nav__link-text">View</span>
                    </a>
                </li>
            @endcan
            @can('update', $model)
                <li class="kt-nav__item">
                    <a href="{{ route('titles.edit', $model) }}" class="kt-nav__link">
                        <i class="kt-nav__link-icon flaticon2-contract"></i>
                        <span class="kt-nav__link-text">Edit</span>
                    </a>
                </li>
            @endcan
            @can('retire', $model)
                <li class="kt-nav__item">
                    <form action="{{ route('titles.retire', $model) }}" method="post" class="kt-nav__link">
                        @csrf
                        <button class="btn w-100 text-left p-0">
                            <i class="kt-nav__link-icon flaticon2-contract"></i>
                            <span class="kt-nav__link-text">Retire</span>
                        </button>
                    </form>
                </li>
            @endcan
            @can('unretire', $model)
                <li class="kt-nav__item">
                    <form action="{{ route('titles.unretire', $model) }}" method="post" class="kt-nav__link">
                        @csrf
                        <button class="btn w-100 text-left p-0">
                            <i class="kt-nav__link-icon flaticon2-contract"></i>
                            <span class="kt-nav__link-text">Unretire</span>
                        </button>
                    </form>
                </li>
            @endcan
            @can('activate', $model)
                <li class="kt-nav__item">
                    <form action="{{ route('titles.activate', $model) }}" method="post" class="kt-nav__link">
                        @csrf
                        <button class="btn w-100 text-left p-0">
                            <i class="kt-nav__link-icon flaticon2-contract"></i>
                            <span class="kt-nav__link-text">Activate</span>
                        </button>
                    </form>
                </li>
            @endcan
        </ul>
    </div>
</div>
