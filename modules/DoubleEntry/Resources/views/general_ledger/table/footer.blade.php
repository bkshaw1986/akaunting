<tfoot>
    <x-table.tr>
        <x-table.td class="w-6/12 py-2 ltr:text-left rtl:text-right text-black-400 font-bold" override="class" colspan="2">
            {{ trans('double-entry::general.totals_balance') }}
        </x-table.td>
        <x-table.td class="w-2/12 py-2 ltr:text-right rtl:text-left text-black-400 font-medium text-xs" override="class">
            @money($class->footer_totals[$table_key]['debit'], setting('default.currency'), true)
        </x-table.td>
        <x-table.td class="w-2/12 py-2 ltr:text-right rtl:text-left text-black-400 font-medium text-xs" override="class">
            @money($class->footer_totals[$table_key]['credit'], setting('default.currency'), true)
        </x-table.td>
        <x-table.td class="w-2/12 py-2 ltr:text-right rtl:text-left text-black-400 font-medium text-xs" override="class">
            @money($class->footer_totals[$table_key]['balance'], setting('default.currency'), true)
        </x-table.td>
    </x-table.tr>
    <x-table.tr class="relative flex items-center px-1 group hover:bg-gray-100" override="class">
        <x-table.td class="w-10/12 pb-8 ltr:text-left rtl:text-right text-black-400 font-bold" override="class" colspan="4">
            {{ trans('double-entry::general.balance_change') }}
        </x-table.td>
        <x-table.td class="w-2/12 pb-8 ltr:text-right rtl:text-left text-black-400 font-medium text-xs" override="class">
            @money($class->footer_totals[$table_key]['balance_change'], setting('default.currency'), true)
        </x-table.td>
    </x-table.tr>
</tfoot>