<x-layouts.admin>
    <x-slot name="title">{{ trans('double-entry::general.journal_entry') }}</x-slot>

    <x-slot name="buttons">
        @can('create-double-entry-journal-entry')
            <x-link href="{{ route('double-entry.journal-entry.create') }}" kind="primary">
                {{ trans('general.title.new', ['type' => trans('double-entry::general.journal_entry')]) }}
            </x-link>
        @endcan
    </x-slot>

    <x-slot name="moreButtons">
        <x-dropdown id="dropdown-more-actions">
            <x-slot name="trigger">
                <span class="material-icons">more_horiz</span>
            </x-slot>

            @can('create-double-entry-journal-entry')
                <x-dropdown.link href="{{ route('import.create', ['double-entry', 'journal-entry']) }}">
                    {{ trans('import.import') }}
                </x-dropdown.link>
            @endcan

            <x-dropdown.link href="{{ route('double-entry.journal-entry.export', request()->input()) }}">
                {{ trans('general.export') }}
            </x-dropdown.link>
        </x-dropdown>
    </x-slot>

    <x-slot name="content">
        @if ($journals->count() || request()->get('search', false))
            <x-index.container>
                <x-index.search
                    search-string="Modules\DoubleEntry\Models\Journal"
                    bulk-action="Modules\DoubleEntry\BulkActions\JournalEntry"
                />

                <x-table>
                    <x-table.thead>
                        <x-table.tr class="flex items-center px-1">
                            <x-table.th class="pr-6 hidden-mobile" override="class">
                                <x-index.bulkaction.all />
                            </x-table.th>
                            <x-table.th class="w-2/12">
                                {{ trans_choice('general.numbers', 1) }}
                            </x-table.th>
                            <x-table.th class="w-2/12">
                                <x-sortablelink column="paid_at" title="{{ trans('general.date') }}" />
                            </x-table.th>
                            <x-table.th class="w-3/12">
                                {{ trans('general.description') }}
                            </x-table.th>
                            <x-table.th class="w-3/12">
                                {{ trans('general.reference') }}
                            </x-table.th>
                            <x-table.th class="w-2/12" kind="amount">
                                <x-sortablelink column="amount" title="{{ trans('general.amount') }}" />
                            </x-table.th>
                        </x-table.tr>
                    </x-table.thead>

                    <x-table.tbody>
                        @foreach($journals as $item)
                            <x-table.tr href="{{ route('double-entry.journal-entry.show', $item->id) }}">
                                <x-table.td class="pr-6 hidden-mobile" override="class">
                                    <x-index.bulkaction.single id="{{ $item->id }}" name="journal_{{ $item->id }}" />
                                </x-table.td>
                                <x-table.td class="w-2/12">
                                    <a href="{{ route('double-entry.journal-entry.show', $item->id) }}">
                                        {{ $item->journal_number }}
                                    </a>
                                </x-table.td>
                                <x-table.td class="w-2/12">
                                    @date($item->paid_at)
                                </x-table.td>
                                <x-table.td class="w-3/12 truncate">
                                    {{ $item->description }}
                                </x-table.td>
                                <x-table.td class="w-3/12 relative">
                                    <div class="{{ empty($item->reference) ? 'mt-4' : '' }}">
                                        {{ $item->reference }}
                                    </div>
                                </x-table.td>
                                <x-table.td class="w-2/12" kind="amount">
                                    @money($item->amount, $item->currency_code ?? setting('default.currency'), true)
                                </x-table.td>

                                <x-table.td class="p-0" override="class">
                                    <x-table.actions :model="$item" />
                                </x-table.td>
                            </x-table.tr>
                        @endforeach
                    </x-table.tbody>
                </x-table>

                <x-pagination :items="$journals" />
            </x-index.container>
        @else
            <x-empty-page
                group="double-entry"
                page="journal-entry"
                image-empty-page="{{ asset('modules/DoubleEntry/Resources/assets/img/manual-journal.png') }}"
                text-page="double-entry::general.journal_entry"
                url-docs-path="https://akaunting.com/docs/app-manual/accounting/double-entry"
                permission-create="create-double-entry-journal-entry"
            />
        @endif
    </x-slot>

    <x-script alias="double-entry" file="journal-entries" />
</x-layouts.admin>
