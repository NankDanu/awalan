<div class="kt-card kt-card-grid">
    <div class="kt-card-header">
        @isset($headerActions)
            <div class="kt-card-toolbar">
                {{ $headerActions }}
            </div>
        @endisset

        @isset($toolbar)
            <div class="kt-card-group">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    {{ $toolbar }}
                </div>
            </div>
        @endisset
    </div>

    <div class="kt-card-content kt-card-table">
        <div class="kt-table-wrapper" data-kt-datatable>
            {{ $slot }}
        </div>
    </div>

    @isset($footer)
        <div class="kt-card-footer kt-datatable-toolbar">
            {{ $footer }}
        </div>
    @endisset
</div>
