@if ($sub_account->sub_accounts)
    @if ($loop->first)
        <x-table.tr
            href="{{ route('double-entry.chart-of-accounts.index') }}"
            class="relative flex items-center border-b hover:bg-gray-100 px-1 group transition-height collapse-sub"
            data-collapse="child-{{ $parent_account->id }}">
            <x-table.td class="w-1/12 pr-10 hidden-mobile">
            </x-table.td>
            <x-table.td class="w-2/12" style="padding-left: {{ $tree_level * 25 }}px;">
                <div class="flex items-center">
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>
                    {{ $parent_account->code }}
                </div>
            </x-table.td>
            <x-table.td class="w-7/12" style="padding-left: {{ $tree_level * 25 }}px;">
                {!! $parent_account->name_linked_general_ledger !!}

                @if (! $parent_account->enabled)
                    <x-index.disable text="{{ trans_choice('double-entry::general.chart_of_accounts', 1) }}" />
                @endif
            </x-table.td>
            <x-table.td class="w-2/12" kind="amount">
                {!! $parent_account->balance_without_subaccounts_colorized !!}
            </x-table.td>
        </x-table.tr>
    @endif
    <x-table.tr
        href="{{ route('double-entry.chart-of-accounts.edit', $sub_account->id) }}"
        class="relative flex items-center border-b hover:bg-gray-100 px-1 group transition-height collapse-sub"
        data-collapse="child-{{ $parent_account->id }}">
        <x-table.td class="w-1/12 pr-6 hidden-mobile">
            {{ Form::doubleEntryBulkActionGroup($sub_account->id, $sub_account->name, ['v-model' => 'bulk_action.selected_grouped[' . $sub_account->type->declass->id . ']', 'group' => $sub_account->type->declass->id]) }}
        </x-table.td>
        <x-table.td class="w-2/12" style="padding-left: {{ $tree_level * 25 }}px;">
            <div class="flex items-center">
                @if($sub_account->sub_accounts->count() > 0)
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>
                    {{ $sub_account->code }}
                    <button type="button" class="leading-none" node="child-{{ $sub_account->id }}" onClick="toggleSub('child-{{ $sub_account->id }}', event)">
                        <span class="material-icons transform transition-all rotate-90 text-lg leading-none">navigate_next</span>
                    </button>
                @else
                    <span class="material-icons transform mr-1 text-lg leading-none">subdirectory_arrow_right</span>
                    {{ $sub_account->code }}
                @endif
            </div>
        </x-table.td>
        <x-table.td class="w-7/12" style="padding-left: {{ $tree_level * 25 }}px;">
            {!! $sub_account->name_linked_general_ledger !!}

            @if (! $sub_account->enabled)
                <x-index.disable text="{{ trans_choice('double-entry::general.chart_of_accounts', 1) }}" />
            @endif
        </x-table.td>
        <x-table.td class="w-2/12" kind="amount">
            {!! $sub_account->balance_colorized !!}
        </x-table.td>

        <x-table.td class="p-0" override="class">
            <x-table.actions :model="$sub_account" />
        </x-table.td>
    </x-table.tr>
    @php
        $parent_account = $sub_account;
        $tree_level++;
    @endphp
    @foreach($sub_account->sub_accounts as $sub_account)
        @php
            $sub_account->load(['type.declass', 'sub_accounts']);
        @endphp
        @include('double-entry::chart_of_accounts.sub_account', ['parent_account' => $parent_account, 'sub_account' => $sub_account, 'tree_level' => $tree_level])
    @endforeach
@endif