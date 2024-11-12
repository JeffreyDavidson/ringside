<div class="flex">
    <div class="flex flex-col p-0 m-0">
        <button
            class="grow inline-flex items-center cursor-pointer leading-4 rounded-md border border-solid border-transparent outline-none h-8 ps-3 pe-3 font-medium text-xs justify-center shrink-0 p-0 gap-0 w-8 text-gray-700 bg-transparent">
            <i class="ki-filled ki-dots-vertical"></i>
        </button>
        <div
            class="p-0 m-0 hidden border border-solid border-gray-200 shadow-[0_7px_18px_0_rgba(0,0,0,0.09)] bg-white rounded-xl">
            @if ($links['view'] ?? true)
                <div class="menu-item">
                    <a href="{{ route($path . '.show', $rowId) }}">
                        <span class="menu-icon">
                            <i class="ki-filled ki-search-list"></i>
                        </span>
                        <span class="menu-title">View</span>
                    </a>
                </div>
            @endif

            <div class="menu-separator"></div>

            @if ($links['edit'] ?? true)
                <div class="menu-item">
                    <a href="{{ route($path . '.edit', $rowId) }}">
                        <span class="menu-icon">
                            <i class="ki-filled ki-pencil"></i>
                        </span>
                        <span class="menu-title">Edit</span>
                    </a>
                </div>
            @endif

            @if ($links['delete'] ?? true)
                <div class="menu-item">
                    <form action="{{ route($path . '.destroy', $rowId) }}" class="d-inline" method="POST" x-data
                        @submit.prevent="if (confirm('Are you sure you want to delete this user?')) $el.submit()">
                        @method('DELETE')
                        @csrf

                        <button type="submit" class="btn btn-link">
                            <i class="fa-solid fa-trash"></i>
                            Remove
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
