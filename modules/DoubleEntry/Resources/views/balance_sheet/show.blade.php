<x-layouts.admin>
    <x-slot name="title">
        {{ $class->model->name }}
    </x-slot>

    <x-slot name="favorite"
        title="{{ $class->model->name }}"
        icon="{{ $class->icon }}"
        :route="['reports.show', $class->model->id]"
    ></x-slot>

    <x-slot name="buttons">

    </x-slot>

    <x-slot name="content">
        <div class="my-10">
            @include($class->views['filter'])

            @include($class->views[$class->type])
        </div>
    </x-slot>

    <x-script folder="common" file="reports" />
</x-layouts.admin>
