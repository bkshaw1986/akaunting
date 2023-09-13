<x-show.accordion type="journals" :open="false">
    <x-slot name="head">
        <x-show.accordion.head
            :title="trans_choice('double-entry::general.journals', 1)"
            :description="trans('double-entry::general.journals_description')"
        />
    </x-slot>

    <x-slot name="body" class="block" override="class">
        <x-table>
            <x-table.thead>
                <x-table.tr class="relative flex items-center px-1 group">
                    <x-table.th class="w-2/4">{{ trans_choice('general.accounts', 1) }}</x-table.th>
                    <x-table.th class="w-1/4">{{ trans('double-entry::general.debit') }}</x-table.th>
                    <x-table.th class="w-1/4">{{ trans('double-entry::general.credit') }}</x-table.th>
                </x-table.tr>
            </x-table.thead>
            <x-table.tbody>
                @foreach($referenceDocument->ledgers as $ledger)
                <x-table.tr>
                    <x-table.td class="w-2/4">{{ $ledger->account->trans_name }}</x-table.td>
                    <x-table.td class="w-1/4 ltr:pr-6 rtl:pl-6 ltr:text-left rtl:text-right py-4 whitespace-nowrap text-sm font-normal text-black" override="class">@money($ledger->debit ?? 0, $referenceDocument->currency_code, true)</x-table.td>
                    <x-table.td class="w-1/4 ltr:pr-6 rtl:pl-6 ltr:text-left rtl:text-right py-4 whitespace-nowrap text-sm font-normal text-black" override="class">@money($ledger->credit ?? 0, $referenceDocument->currency_code, true)</x-table.td>
                </x-table.tr>
                @endforeach
            </x-table.tbody>
        </x-table>
    </x-slot>
</x-show.accordion>
