<x-layouts.admin>
    <x-slot name="title">
        {{ trans('general.title.new', ['type' => trans_choice('general.accounts', 1)]) }}
    </x-slot>

    <x-slot name="content">
        <x-form.container>
            <x-form id="chart-of-account" method="POST" route="double-entry.chart-of-accounts.store">
                <x-form.section>
                    <x-slot name="head">
                        <x-form.section.head title="{{ trans('general.general') }}" description="{{ trans('double-entry::general.form_description.chart_of_accounts.general') }}" />
                    </x-slot>

                    <x-slot name="body">
                        <x-form.group.text name="name" :label="trans('general.name')" />

                        <x-form.group.text name="code" :label="trans('general.code')" />

                        <x-form.group.select
                            name="type_id"
                            :label="trans_choice('general.types', 1)"
                            :options="$types"
                            change="updateParentAccounts"
                            group
                        />

                        <div class="sm:col-span-3"></div>

                        <x-form.group.toggle
                            name="is_sub_account"
                            label="{{ ucwords(trans('general.is')) . ' ' . trans('double-entry::general.sub') . '-' . trans_choice('general.accounts', 1) }}?"
                        />

                        <x-form.group.select
                            name="account_id"
                            :label="trans_choice('double-entry::general.parents', 1) . ' ' . trans_choice('general.accounts', 1)"
                            v-disabled="!isSubAccount"
                            dynamicOptions="accountsBasedTypes"
                            group
                            not-required
                        />

                        <x-form.group.textarea
                            name="description"
                            :label="trans('general.description')"
                            not-required
                        />

                        <x-form.group.switch name="enabled" :label="trans('general.enabled')" :value="true" />

                        <x-form.input.hidden name="accounts" value="{{ json_encode($accounts) }}" />
                    </x-slot>
                </x-form.section>

                <x-form.section>
                    <x-slot name="foot">
                        <x-form.buttons :cancel="url()->previous()" />
                    </x-slot>
                </x-form.section>
            </x-form>
        </x-form.container>
    </x-slot>

    @push('scripts_start')
        <script src="{{ asset('modules/DoubleEntry/Resources/assets/js/chart-of-accounts.min.js?v=' . module_version('double-entry')) }}"></script>
    @endpush
</x-layouts.admin>