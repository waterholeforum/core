@php
    $title = isset($page)
        ? __('waterhole::cp.edit-page-title')
        : __('waterhole::cp.create-page-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.structure')"
        :parent-title="__('waterhole::cp.structure-title')"
        :title="$title"
    />

    <x-waterhole::form
        :fields="$form->fields()"
        :method="isset($page) ? 'PATCH' : 'POST'"
        action="{{ isset($page) ? route('waterhole.cp.structure.pages.update', compact('page')) : route('waterhole.cp.structure.pages.store') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form slugger"
    />
</x-waterhole::cp>
