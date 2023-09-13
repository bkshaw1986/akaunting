<x-form.group.select
    name="country"
    label="{!! trans_choice('general.countries', 1) !!}"
    :options="trans('countries')"
    :selected="setting('company.country')"
    required="{{ $required }}"
    not-required="{{ $notRequired }}"
    model="form.country"
    form-group-class="{{ $formGroupClass }}"
/>
