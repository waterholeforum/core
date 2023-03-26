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

    <form
        method="POST"
        action="{{ isset($user) ? route('waterhole.cp.users.update', compact('user')) : route('waterhole.cp.users.store') }}"
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
                    :default="route('waterhole.cp.users.index')"
                    class="btn"
                />
            </div>
        </div>
    </form>
</x-waterhole::cp>
