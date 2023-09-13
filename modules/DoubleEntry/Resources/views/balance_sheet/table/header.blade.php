<x-table.thead>
    <x-table.tr class="border-b border-purple">
        <x-table.th class="w-10/12 ltr:text-left rtl:text-right text-xl text-purple font-semibold pt-8" override="class">
            {{ trans($de_class->name) }}
            <button type="button" class="leading-none align-text-top" onClick="toggleSub('class-{{ $de_class->id }}', event)">
                <span class="material-icons transform transition-all text-lg leading-none">navigate_next</span>
            </button>
        </x-table.th>
        <x-table.th class="w-2/12 ltr:text-right rtl:text-left text-lg font-semibold pt-8" override="class">
            @money($de_class->total, setting('default.currency'), true)
        </x-table.th>
    </x-table.tr>
</x-table.thead>