@php
    $title = isset($channel)
        ? __('waterhole::admin.edit-channel-title')
        : __('waterhole::admin.create-channel-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        :parent-title="__('waterhole::admin.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($channel) ? route('waterhole.admin.structure.channels.update', compact('channel')) : route('waterhole.admin.structure.channels.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($channel)) @method('PATCH') @endif

        <div class="stack gap-lg" data-controller="slugger">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                @components($form->fields())
            </div>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($channel) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a
                    href="{{ route('waterhole.admin.structure') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
