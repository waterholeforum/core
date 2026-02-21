@php
    $title = isset($channel)
        ? __('waterhole::cp.edit-channel-title')
        : __('waterhole::cp.create-channel-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.structure')"
        :parent-title="__('waterhole::cp.structure-title')"
        :title="$title"
    />

    <x-waterhole::form
        :fields="$form->fields()"
        :method="isset($channel) ? 'PATCH' : 'POST'"
        action="{{ isset($channel) ? route('waterhole.cp.structure.channels.update', compact('channel')) : route('waterhole.cp.structure.channels.store') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form slugger"
    />
</x-waterhole::cp>
