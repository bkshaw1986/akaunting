<x-table.tr>
    <x-table.td class="w-10/12 ltr:text-left rtl:text-right text-black-400 pl-5 pt-5" override="class">
        {{ trans($type->name) }}
        <button type="button" class="leading-none align-text-top" onClick="toggleSub('type-{{ $type->id }}', event)">
            <span class="material-icons transform transition-all text-lg leading-none">navigate_next</span>
        </button>
    </x-table.td>
    <x-table.td class="w-2/12 ltr:text-right rtl:text-left text-black-400 text-xs pt-5" override="class">
        @money($type->total, setting('default.currency'), true)
    </x-table.td>
</x-table.tr>
@foreach($class->de_accounts[$type->id] as $account)
    <x-table.tr data-collapse="type-{{ $type->id }}" class="active-collapse">
        <x-table.td class="w-10/12 ltr:text-left rtl:text-right text-black-400 pl-10" override="class">
            {!! $account->name_linked_general_ledger !!}
        </x-table.td>
        <x-table.td class="w-2/12 ltr:text-right rtl:text-left text-black-400 text-xs" override="class">
            @money($account->de_balance, setting('default.currency'), true)
        </x-table.td>
    </x-table.tr>
@endforeach