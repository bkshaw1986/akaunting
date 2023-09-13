<x-table.thead>
    <x-table.tr class="border-b border-purple">
        <x-table.th class="w-8/12 ltr:text-left rtl:text-right text-xl text-purple font-semibold" override="class" colspan="3">
            {{ $reference_document->date }} <a href="{{ $reference_document->link }}" class="text-lg font-medium" style="border-bottom: 1px solid;">{{ $reference_document->transaction }}</a>
        </x-table.th>
    </x-table.tr>
</x-table.thead>