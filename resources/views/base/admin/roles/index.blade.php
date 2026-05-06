<x-layouts.admin :title="'Manajemen Role'" :pageTitle="'Daftar Role'" :showComments="false">
    <x-slot:toolbarActions>
        @can('create-roles')
            <a href="{{ route('roles.create') }}" class="kt-btn kt-btn-outline">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Role
            </a>
        @endcan
    </x-slot:toolbarActions>

    <div class="grid w-full space-y-5">
        <div class="kt-card">
            <div class="kt-card-header min-h-16">
                <div class="flex w-full flex-wrap items-center justify-between gap-3">
                    <div id="roles-search" class="flex-1 min-w-[220px]"></div>
                </div>
            </div>

            <div class="kt-card-table">
                <div class="kt-table-wrapper kt-scrollable">
                    <table id="roles-table" class="kt-table" data-kt-datatable-table>
                        <thead>
                            <tr>
                                <th>
                                    <div class="kt-table-col">
                                        <span class="kt-table-col-label">Nama</span>
                                        <span class="kt-table-col-sort"></span>
                                    </div>
                                </th>
                                <th>
                                    <div class="kt-table-col">
                                        <span class="kt-table-col-label">Permission</span>
                                        <span class="kt-table-col-sort"></span>
                                    </div>
                                </th>
                                <th class="text-center">
                                    <div class="kt-table-col justify-center">
                                        <span class="kt-table-col-label">Jumlah</span>
                                        <span class="kt-table-col-sort"></span>
                                    </div>
                                </th>
                                <th class="text-end" data-kt-datatable-column-sort="false">
                                    <div class="kt-table-col justify-end">
                                        <span class="kt-table-col-label">Aksi</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="kt-datatable-toolbar">
                    <div id="roles-length" class="kt-datatable-length"></div>
                    <div class="kt-datatable-info">
                        <div id="roles-info" class="kt-datatable-info text-sm"></div>
                        <div id="roles-paging" class="kt-datatable-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#roles-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                autoWidth: false,
                ajax: {
                    url: '{{ route('roles.datatable') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'permissions', name: 'permissions', orderable: false },
                    { data: 'count', name: 'count', className: 'text-center' },
                    { data: 'actions', orderable: false, searchable: false },
                ],
                order: [[0, 'asc']],
                orderClasses: false,
                language: {
                    search: '',
                    searchPlaceholder: 'Search Roles',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    zeroRecords: 'Data tidak ditemukan',
                    processing: 'Memuat...'
                }
            });

            $('#roles-search').append($('#roles-table_wrapper .dataTables_filter'));
            $('#roles-length').append($('#roles-table_wrapper .dataTables_length'));
            $('#roles-info').append($('#roles-table_wrapper .dataTables_info'));
            $('#roles-paging').append($('#roles-table_wrapper .dataTables_paginate'));

            const applyKtuiDatatableStyles = function () {
                const $filter = $('#roles-search .dataTables_filter');
                $filter.addClass('flex items-center gap-2');
                $filter.find('input').addClass('kt-input kt-input-sm min-w-[220px]');

                const $length = $('#roles-length .dataTables_length');
                $length.addClass('kt-datatable-length');
                $length.find('select').addClass('kt-select kt-select-sm');

                $('#roles-info .dataTables_info').addClass('kt-datatable-info');

                const $paging = $('#roles-paging .dataTables_paginate');
                $paging.addClass('kt-datatable-pagination');
                $paging.find('.paginate_button').each(function () {
                    const $button = $(this);
                    $button.addClass('kt-datatable-pagination-button');

                    if ($button.hasClass('previous')) {
                        $button.addClass('kt-datatable-pagination-prev');
                    }

                    if ($button.hasClass('next')) {
                        $button.addClass('kt-datatable-pagination-next');
                    }

                    if ($button.hasClass('ellipsis')) {
                        $button.addClass('kt-datatable-pagination-more');
                    }
                });

                $('#roles-table_wrapper .dataTables_processing').addClass('kt-datatable-loading');
            };

            table.on('draw.dt', applyKtuiDatatableStyles);
            applyKtuiDatatableStyles();
        });
    </script>
</x-layouts.admin>
