<x-table.thead>
    <x-table.tr class="border-b border-purple">
        <x-table.th class="ltr:text-left rtl:text-right text-xl text-purple font-bold" override="class" colspan="5">
            {{ $table_name }}
        </x-table.th>
    </x-table.tr>
    <x-table.tr class="relative flex items-center px-1 group border-b hover:bg-gray-100" override="class">
        <x-table.th class="w-10/12 ltr:text-left rtl:text-right text-black-400 font-bold py-2" override="class" colspan="4">
            {{ trans('accounts.opening_balance') }}
        </x-table.th>
        <x-table.th class="w-2/12 ltr:text-right rtl:text-left text-black-400 font-medium text-xs py-2" override="class">
            @money($class->opening_balances[$table_key], setting('default.currency'), true)
        </x-table.th>
    </x-table.tr>
</x-table.thead>