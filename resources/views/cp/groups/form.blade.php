@php
    $title = isset($group)
        ? __('waterhole::cp.edit-group-title')
        : __('waterhole::cp.create-group-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.groups.index')"
        :parent-title="__('waterhole::cp.groups-title')"
        :title="$title"
    />

    <x-waterhole::form
        :fields="$form->fields()"
        :method="isset($group) ? 'PATCH' : 'POST'"
        action="{{ isset($group) ? route('waterhole.cp.groups.update', compact('group')) : route('waterhole.cp.groups.store') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form"
        :panel-attributes="['class' => 'stack dividers card card__body']"
    />
</x-waterhole::cp>
