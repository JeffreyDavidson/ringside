<table class="table table-striped table-bordered table-hover" id="resourceTable">
    <thead>
        <tr>
            <th>Wrestler Name</th>
            <th>Hometown</th>
            <th>Hired Date</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @forelse ($wrestlers as $wrestler)
        <tr>
            <td>{{ $wrestler->name }}</td>
            <td>{{ $wrestler->hometown }}</td>
            <td>{{ $wrestler->formatted_hired_at }}</td>
            <td>{{ $wrestler->status }}</td>
            <td nowrap>
                @can('update', $wrestler)
                    <a href="{{ route('wrestlers.edit', $wrestler) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit">
                        <i class="la la-edit"></i>
                    </a>
                @endcan
                @can('view', $wrestler)
                    <a href="{{ route('wrestlers.show', $wrestler) }}" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="View">
                        <i class="la la-eye"></i>
                    </a>
                @endcan
                @can('delete', $wrestler)
                    <form class="d-inline-block" action="{{ route('wrestlers.destroy', $wrestler) }}">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete">
                            <i class="la la-trash"></i>
                        </button>
                    </form>
                @endcan
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5">There are currently 0 wrestlers in the system.</td>
        </tr>
    @endforelse
    </tbody>
</table>
