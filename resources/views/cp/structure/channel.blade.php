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

    <form
        method="POST"
        action="{{ isset($channel) ? route('waterhole.cp.structure.channels.update', compact('channel')) : route('waterhole.cp.structure.channels.store') }}"
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
                    href="{{ route('waterhole.cp.structure') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::cp>
