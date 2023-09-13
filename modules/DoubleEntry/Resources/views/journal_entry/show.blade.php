<x-layouts.admin>
    <x-slot name="title">
        {{ trans('double-entry::general.journal_entry') . ': ' . $journal_entry->journal_number }}
    </x-slot>

    <x-slot name="buttons">
        <x-transactions.show.buttons
            type="journal"
            :transaction="$journal_entry"
            text-button-add-new="{{ trans('general.title.new', ['type' => trans('double-entry::general.journal_entry')]) }}"
        />
    </x-slot>

    <x-slot name="content">
        <x-transactions.show.content
            type="journal"
            :transaction="$journal_entry"
            hide-header-account
            hide-header-category
            hide-header-contact
            hide-account
            hide-category
            hide-contact
            hide-schedule
            hide-children
            hide-payment-methods
            hide-footer-histories
            text-header-account=""
            text-description="{{ trans('general.description') }}"
            text-content-title="{{ trans('double-entry::general.journal_entry') }}"
        />
    </x-slot>

    @push('stylesheet')
        <link rel="stylesheet" href="{{ asset('public/css/print.css?v=' . version('short')) }}" type="text/css">
    @endpush

    <x-transactions.script type="journal" />
</x-layouts.admin>
