<div class="table-responsive my-8">
    <table class="w-full rp-border-collapse">
        <x-table.thead>
            <x-table.tr>
                <x-table.th class="w-2/12 ltr:text-left rtl:text-right text-purple font-medium text-xs uppercase" override="class">{{ trans('general.date') }}</x-table.th>
                <x-table.th class="w-4/12 ltr:text-left rtl:text-right text-purple font-medium text-xs uppercase" override="class">{{ trans_choice('general.transactions', 1) }}</x-table.th>
                <x-table.th class="w-2/12 ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase" override="class">{{ trans('double-entry::general.debit') }}</x-table.th>
                <x-table.th class="w-2/12 ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase" override="class">{{ trans('double-entry::general.credit') }}</x-table.th>
                <x-table.th class="w-2/12 ltr:text-right rtl:text-left text-purple font-medium text-xs uppercase" override="class">{{ trans('general.balance') }}</x-table.th>
            </x-table.tr>
        </x-table.thead>
    </table>
</div>
