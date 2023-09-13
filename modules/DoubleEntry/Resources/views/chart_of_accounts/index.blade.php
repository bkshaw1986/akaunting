<x-layouts.admin>
    <x-slot name="title">{{ trans_choice('double-entry::general.chart_of_accounts', 2) }}</x-slot>

    <x-slot name="favorite"
        title="{{ trans_choice('double-entry::general.chart_of_accounts', 2) }}"
        icon="balance"
        route="double-entry.chart-of-accounts.index"
    ></x-slot>

    <x-slot name="buttons">
        @can('create-double-entry-chart-of-accounts')
            <x-link href="{{ route('double-entry.chart-of-accounts.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans_choice('double-entry::general.chart_of_accounts', 1)]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons">more_horiz</span>
            </x-slot>

            @can('create-double-entry-chart-of-accounts')
                <x-dropdown.link href="{{ route('import.create', ['double-entry', 'chart-of-accounts']) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('double-entry.chart-of-accounts.export', request()->input()) }}">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        <x-index.container>
            <x-index.search
                search-string="Modules\DoubleEntry\Models\Account"
                bulk-action="Modules\DoubleEntry\BulkActions\ChartOfAccounts"
            />

            @foreach($classes as $class)
                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th class="text-3xl">
                                {{ trans($class->name) }}
                            </x-table.th>
                        </x-table.tr>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th class="w-1/12">
                                {{ Form::doubleEntryBulkActionAllGroup(['v-model' => 'bulk_action.select_all[' . $class->id . ']', 'group' => $class->id]) }}
                            </x-table.th>
                            <x-table.th class="w-2/12">{{ trans('general.code') }}</x-table.th>
                            <x-table.th class="w-4/12">{{ trans('general.name') }}</x-table.th>
                            <x-table.th class="w-3/12">{{ trans_choice('general.types', 1) }}</x-table.th>
                            <x-table.th class="w-2/12" kind="amount">{{ trans('general.balance') }}</x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <x-table.tbody>
                        @foreach($class->accounts as $account)
                            <x-table.tr href="{{ route('double-entry.chart-of-accounts.edit', $account->id) }}" class="relative flex items-center border-b hover:bg-gray-100 px-1 group transition-[height]">
                                <x-table.td class="w-1/12 ltr:pr-6 rtl:pl-6 hidden sm:table-cell" override="class">
                                    {{ Form::doubleEntryBulkActionGroup($account->id, $account->name, ['v-model' => 'bulk_action.selected_grouped[' . $account->declass->id . ']', 'group' => $account->declass->id]) }}
                                </x-table.td>
                                <x-table.td class="w-2/12">
                                    @if($account->sub_accounts->count() > 0)
                                        <div class="flex items-center">
                                            {{ $account->code }}

                                            <button type="button" class="leading-none align-text-top" node="child-{{ $account->id }}" onClick="toggleSub('child-{{ $account->id }}', event)">
                                                <span class="material-icons transform transition-all rotate-90 text-lg leading-none">navigate_next</span>
                                            </button>
                                        </div>
                                    @else
                                        {{ $account->code }}
                                    @endif
                                </x-table.td>
                                <x-table.td class="w-4/12">
                                    {!! $account->name_linked_general_ledger !!}

                                    @if (! $account->enabled)
                                        <x-index.disable text="{{ trans_choice('double-entry::general.chart_of_accounts', 1) }}" />
                                    @endif
                                </x-table.td>
                                <x-table.td class="w-3/12">{{ trans($account->type->name) }}</x-table.td>
                                <x-table.td class="w-2/12" kind="amount">
                                    {!! $account->balance_colorized !!}
                                </x-table.td>
                                <x-table.td class="p-0" override="class">
                                    <x-table.actions :model="$account" />
                                </x-table.td>
                            </x-table.tr>
                            @foreach($account->sub_accounts as $sub_account)
                                @php
                                    $sub_account->load(['type.declass', 'sub_accounts']);
                                @endphp

                                @include('double-entry::chart_of_accounts.sub_account', ['parent_account' => $account, 'sub_account' => $sub_account, 'tree_level' => 1])
                            @endforeach
                        @endforeach
                    </x-table.tbody>
                </x-table>
            @endforeach
        </x-index.container>
    </x-slot>

    @push('scripts_start')
        <script src="{{ asset('modules/DoubleEntry/Resources/assets/js/chart-of-accounts.min.js?v=' . module_version('double-entry')) }}"></script>
    @endpush
</x-layouts.admin>