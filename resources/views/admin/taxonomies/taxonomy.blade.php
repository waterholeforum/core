@php
    $title = isset($taxonomy)
        ? __('waterhole::admin.edit-taxonomy-title')
        : __('waterhole::admin.create-taxonomy-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.taxonomies.index')"
        :parent-title="__('waterhole::admin.taxonomies-title')"
        :title="$title"
    />

    <div class="stack gap-xl">
        <form
            method="POST"
            action="{{ isset($taxonomy)
                ? route('waterhole.admin.taxonomies.update', compact('taxonomy'))
                : route('waterhole.admin.taxonomies.store') }}"
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
                    href="{{ route('waterhole.admin.taxonomies.index') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </form>

        @isset($taxonomy)
            <details class="card" open>
                <summary class="card__header h5">{{ __('waterhole::admin.taxonomy-tags-title') }}</summary>
                <turbo-frame id="tags">
                    <div class="card__body stack gap-md">
                        <ul class="card" role="list">
                            @forelse ($taxonomy->tags->load('taxonomy') as $tag)
                                <li class="card__row row gap-sm">
                                    {{ Waterhole\emojify($tag->name) }}

                                    <x-waterhole::action-buttons
                                        :for="$tag"
                                        :button-attributes="['class' => 'btn btn--icon btn--transparent']"
                                        icons
                                        class="push-end row -m-sm text-xs"
                                    />
                                </li>
                            @empty
                                <li class="placeholder">No Tags</li>
                            @endforelse
                        </ul>

                        <div>
                            <a
                                href="{{ route('waterhole.admin.taxonomies.tags.create', compact('taxonomy')) }}"
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
</x-waterhole::admin>
