<div class="table-responsive my-8">
    <table class="w-full rp-border-collapse">
        <thead>
            <tr>
                <th class="{{ $class->column_name_width }}"></th>
                <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase">{{ trans('double-entry::general.debit') }}</th>
                <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase">{{ trans('double-entry::general.credit') }}</th>
            </tr>
        </thead>
    </table>
</div>
