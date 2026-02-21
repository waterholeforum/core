@php
    $title = isset($link)
        ? __('waterhole::cp.edit-link-title')
        : __('waterhole::cp.create-link-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.structure')"
        :parent-title="__('waterhole::cp.structure-title')"
        :title="$title"
    />

    <x-waterhole::form
        :fields="$form->fields()"
        :method="isset($link) ? 'PATCH' : 'POST'"
        action="{{ isset($link) ? route('waterhole.cp.structure.links.update', compact('link')) : route('waterhole.cp.structure.links.store') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form"
    />
</x-waterhole::cp>
