<x-table class="datatable">
    <thead>
        <th>Id</th>
        <th>Wrestler Name</th>
        <th>Hometown</th>
        <th>Date Employed</th>
        <th>Status</th>
        <th>Actions</th>
    </thead>
    <tbody>
        @foreach($employedWrestlers as $wrestler)
            <tr>
                <td>{{ $wrestler->id }}</td>
                <td>{{ $wrestler->name }}</td>
                <td>{{ $wrestler->hometown }}</td>
                <td>{{ $wrestler->employed_at->toDateString() }}</td>
                <td>{{ $wrestler->status->label() }}</td>
                <td>
                    @include('wrestlers.partials.action-cell', [
                        'wrestler' => $wrestler,
                        'actions' => collect([
                            'retire', 'employ', 'release', 'suspend', 'reinstate', 'injure', 'clearInjury'
                        ])
                    ])
                </td>
            </tr>
        @endforeach
    </tbody>
</x-table>
<div class="row">
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_paginate paging_full_numbers" id="kt_table_1_paginate">
            <ul class="pagination">
                {{ $employedWrestlers->links() }}
            </ul>
        </div>
    </div>
    <div class="col-sm-12 col-md-5">
        <div class="dataTables_info" id="kt_table_1_info" role="status" aria-live="polite">
            Showing {{ $employedWrestlers->firstItem() }} to {{ $employedWrestlers->lastItem() }} of {{ $employedWrestlers->total() }} entries
        </div>
    </div>
    <div class="col-sm-12 col-md-2">
        <div class="p-3 dataTables_length" id="employed_table_length">
            <label>
                <select name="employed_table_length" aria-controls="employed_table"
                        class="custom-select custom-select-sm form-control form-control-sm"
                >
                    <option value="10">10 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </label>
        </div>
    </div>
</div>
