<x-table.tr>
    <x-table.td class="w-8/12 ltr:text-left rtl:text-right text-black-400" override="class">
        {!! $ledger->account->name_linked_general_ledger !!}
    </x-table.td>
    <x-table.td class="w-2/12 ltr:text-right rtl:text-left text-black-400 text-xs" override="class">
        @money((double) $ledger->debit, setting('default.currency'), true)
    </x-table.td>
    <x-table.td class="w-2/12 ltr:text-right rtl:text-left text-black-400 text-xs" override="class">
        @money((double) $ledger->credit, setting('default.currency'), true)
    </x-table.td>
</x-table.tr>