<div class="table-responsive my-8">
    <table class="w-full rp-border-collapse">
        <thead>
            <tr>
                <th class="{{ $class->column_name_width }} text-right font-medium text-lg">{{ trans_choice('general.totals', 1) }}</th>
                <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left text-purple font-medium text-lg uppercase">@money($class->content_footer_total['debit'], setting('default.currency'), true)</th>
                <th class="{{ $class->column_value_width }} ltr:text-right rtl:text-left text-purple font-medium text-lg uppercase">@money($class->content_footer_total['credit'], setting('default.currency'), true)</th>
            </tr>
        </thead>
    </table>
</div>
