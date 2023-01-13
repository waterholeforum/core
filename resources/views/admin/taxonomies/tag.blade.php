@php
    $title = isset($tag)
        ? __('waterhole::admin.edit-tag-title')
        : __('waterhole::admin.create-tag-title');
@endphp

<x-waterhole::admin :title="$title">
    <turbo-frame id="modal">
        <x-waterhole::dialog :title="$title" class="dialog--sm">
            <form
                method="POST"
                action="{{ isset($tag)
                    ? route('waterhole.admin.taxonomies.tags.update', compact('taxonomy', 'tag'))
                    : route('waterhole.admin.taxonomies.tags.store', compact('taxonomy')) }}"
                enctype="multipart/form-data"
                data-turbo-frame="tags"
            >
                @csrf
                @if (isset($tag)) @method('PATCH') @endif

                <div class="stack gap-lg">
                    <x-waterhole::validation-errors/>

                    @components($form->fields())

                    <div class="row gap-xs wrap">
                        <button
                            type="submit"
                            class="btn bg-accent btn--wide"
                        >
                            {{ isset($tag)
                                ? __('waterhole::system.save-changes-button')
                                : __('waterhole::system.create-button') }}
                        </button>

                        <a
                            href="{{ url()->previous() }}"
                            class="btn"
                            data-action="modal#hide"
                        >{{ __('waterhole::system.cancel-button') }}</a>
                    </div>
                </div>
            </form>
        </x-waterhole::dialog>
    </turbo-frame>
</x-waterhole::admin>
