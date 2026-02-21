@php
    $title = __('waterhole::user.edit-profile-title');
@endphp

<x-waterhole::user-profile :user="Auth::user()" :title="$title">
    <h2 class="visually-hidden">{{ $title }}</h2>

    <x-waterhole::form
        :fields="$form->fields()"
        :submit-label="__('waterhole::system.save-changes-button')"
        :panel-attributes="['class' => 'stack dividers card card__body']"
        action="{{ route('waterhole.preferences.profile') }}"
        enctype="multipart/form-data"
        data-controller="dirty-form"
    />
</x-waterhole::user-profile>
