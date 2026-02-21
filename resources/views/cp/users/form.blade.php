@php
    $title = isset($user)
        ? __('waterhole::cp.edit-user-title')
        : __('waterhole::cp.create-user-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.users.index')"
        :parent-title="__('waterhole::cp.users-title')"
        :title="$title"
    />

    <x-waterhole::form
        :fields="$form->fields()"
        :method="isset($user) ? 'PATCH' : 'POST'"
        action="{{ isset($user) ? route('waterhole.cp.users.update', compact('user')) : route('waterhole.cp.users.store') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form"
    />
</x-waterhole::cp>
