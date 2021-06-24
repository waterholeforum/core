@php
    $title = isset($user)
        ? __('waterhole::admin.edit-user-title')
        : __('waterhole::admin.create-user-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.users.index')"
        :parent-title="__('waterhole::admin.users-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($user) ? route('waterhole.admin.users.update', compact('user')) : route('waterhole.admin.users.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @isset($user) @method('PATCH') @endif
        @return

        <div class="stack gap-lg">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                @components($form->fields())
            </div>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($user) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <x-waterhole::cancel
                    :default="route('waterhole.admin.users.index')"
                    class="btn"
                />
            </div>
        </div>
    </form>
</x-waterhole::admin>
