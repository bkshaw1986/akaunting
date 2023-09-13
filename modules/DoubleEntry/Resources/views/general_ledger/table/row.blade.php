<x-table.tr>
    <x-table.td class="w-2/12 py-2 text-left text-black-400" override="class">
        @date($row['issued_at'])
    </x-table.td>
    @if(!is_null($row['link']))
        <x-table.td class="w-4/12 py-2 text-left text-black-400" override="class">
            <a href="{{ $row['link'] }}" style="border-bottom: 1px solid;">{{ $row['transaction'] }}</a>
        </x-table.td>
    @else
        <x-table.td class="w-4/12 py-2 text-left text-black-400" override="class">
            {{ $row['transaction'] }}
        </x-table.td>
    @endif
    <x-table.td class="w-2/12 py-2 ltr:text-right rtl:text-left text-black-400 text-xs" override="class">
        {{ !empty($row['debit']) ? money($row['debit'], setting('default.currency'), true) : '' }}
    </x-table.td>
    <x-table.td class="w-2/12 py-2 ltr:text-right rtl:text-left text-black-400 text-xs" override="class">
        {{ !empty($row['credit']) ? money($row['credit'], setting('default.currency'), true) : '' }}
    </x-table.td>
    <x-table.td class="w-2/12 py-2 ltr:text-right rtl:text-left text-black-400 text-xs" override="class">
        {{ money($row['balance'], setting('default.currency'), true) }}
    </x-table.td>
</x-table.tr>