<x-layouts.admin :title="'Manajemen Pengguna'" :pageTitle="'Daftar Pengguna'" :showWidget="false">
    <x-slot:toolbarActions>
        @can('create-users')
            <a href="{{ route('users.create') }}" class="kt-btn">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Pengguna
            </a>
        @endcan
    </x-slot:toolbarActions>

    <div class="grid w-full space-y-5">

        <div class="kt-card">
            <div class="kt-card-header min-h-16">
                <div class="flex w-full flex-wrap items-center justify-between gap-3">
                    <div id="users-search" class="flex-1 min-w-[220px]"></div>
                </div>
            </div>

            <div class="kt-card-table">
                <div class="kt-table-wrapper kt-scrollable">
                    <table id="users-table" class="kt-table" data-kt-datatable-table>
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
                                        <span class="kt-table-col-label">Email</span>
                                        <span class="kt-table-col-sort"></span>
                                    </div>
                                </th>
                                <th>
                                    <div class="kt-table-col">
                                        <span class="kt-table-col-label">Peran</span>
                                        <span class="kt-table-col-sort"></span>
                                    </div>
                                </th>
                                <th class="text-center">
                                    <div class="kt-table-col justify-center">
                                        <span class="kt-table-col-label">Status</span>
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
                    <div id="users-length" class="kt-datatable-length"></div>
                    <div class="kt-datatable-info">
                        <div id="users-info" class="kt-datatable-info text-sm"></div>
                        <div id="users-paging" class="kt-datatable-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 10,
                autoWidth: false,
                ajax: {
                    url: '{{ route('users.datatable') }}',
                    type: 'GET'
                },
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role', orderable: false },
                    { data: 'status', name: 'status' },
                    { data: 'actions', orderable: false, searchable: false },
                ],
                order: [[0, 'asc']],
                orderClasses: false,
                language: {
                    search: '',
                    searchPlaceholder: 'Search Users',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    zeroRecords: 'Data tidak ditemukan',
                    processing: 'Memuat...'
                }
            });

            $('#users-search').append($('#users-table_wrapper .dataTables_filter'));
            $('#users-length').append($('#users-table_wrapper .dataTables_length'));
            $('#users-info').append($('#users-table_wrapper .dataTables_info'));
            $('#users-paging').append($('#users-table_wrapper .dataTables_paginate'));

            const applyKtuiDatatableStyles = function () {
                const $filter = $('#users-search .dataTables_filter');
                $filter.addClass('flex items-center gap-2');
                $filter.find('input').addClass('kt-input kt-input-sm min-w-[220px]');

                const $length = $('#users-length .dataTables_length');
                $length.addClass('kt-datatable-length');
                $length.find('select').addClass('kt-select kt-select-sm');

                $('#users-info .dataTables_info').addClass('kt-datatable-info');

                const $paging = $('#users-paging .dataTables_paginate');
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

                $('#users-table_wrapper .dataTables_processing').addClass('kt-datatable-loading');
            };

            table.on('draw.dt', applyKtuiDatatableStyles);
            applyKtuiDatatableStyles();
        });
    </script>
</x-layouts.admin>
