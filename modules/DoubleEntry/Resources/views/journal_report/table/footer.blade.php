<tfoot>
    <x-table.tr class="border-t border-purple">
        <x-table.td class="w-8/12 ltr:text-right rtl:text-left text-black-400 font-bold pb-8" override="class">
            {{ trans_choice('general.totals', 1) }}
        </x-table.td>
        <x-table.td class="w-2/12 ltr:text-right rtl:text-left text-black-400 font-medium text-xs pb-8" override="class">
            {{ $reference_document->debit_total }}
        </x-table.td>
        <x-table.td class="w-2/12 ltr:text-right rtl:text-left text-black-400 font-medium text-xs pb-8" override="class">
            {{ $reference_document->credit_total }}
        </x-table.td>
    </x-table.tr>
</tfoot>
