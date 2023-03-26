@php
    $title = isset($taxonomy)
        ? __('waterhole::cp.edit-taxonomy-title')
        : __('waterhole::cp.create-taxonomy-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.taxonomies.index')"
        :parent-title="__('waterhole::cp.taxonomies-title')"
        :title="$title"
    />

    <div class="stack gap-xl">
        <form
            method="POST"
            action="{{ isset($taxonomy)
                ? route('waterhole.cp.taxonomies.update', compact('taxonomy'))
                : route('waterhole.cp.taxonomies.store') }}"
            enctype="multipart/form-data"
            class="stack gap-md"
        >
            @csrf
            @if (isset($taxonomy))
                @method('PATCH')
            @endif

            <x-waterhole::validation-errors/>

            @components($form->fields())

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
                >
                    {{ isset($taxonomy)
                        ? __('waterhole::system.save-changes-button')
                        : __('waterhole::system.continue-button') }}
                </button>

                <a
                    href="{{ route('waterhole.cp.taxonomies.index') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </form>

        @isset($taxonomy)
            <details class="card" open>
                <summary class="card__header h5">{{ __('waterhole::cp.taxonomy-tags-title') }}</summary>
                <turbo-frame id="tags" data-action="turbo:frame-load->page#closeModal">
                    <div class="card__body stack gap-md">
                        <ul class="card" role="list">
                            @foreach ($taxonomy->tags->load('taxonomy') as $tag)
                                <x-waterhole::cp.tag-row :tag="$tag"/>
                            @endforeach
                            <li class="placeholder hide-if-not-only-child" id="tag-list-end">No Tags</li>
                        </ul>

                        <div>
                            <a
                                href="{{ route('waterhole.cp.taxonomies.tags.create', compact('taxonomy')) }}"
                                class="btn"
                                data-turbo-frame="modal"
                            >
                                <x-waterhole::icon icon="tabler-plus"/>
                                Add
                            </a>
                        </div>
                    </div>
                </turbo-frame>
            </details>
        @endisset
    </div>
</x-waterhole::cp>
