@php
    $title = isset($page)
        ? __('waterhole::admin.edit-page-title')
        : __('waterhole::admin.create-page-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        :parent-title="__('waterhole::admin.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($page) ? route('waterhole.admin.structure.pages.update', compact('page')) : route('waterhole.admin.structure.pages.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($page)) @method('PATCH') @endif

        <div class="stack-lg" data-controller="slugger">
            <x-waterhole::validation-errors/>

            <div class="stack-md">
                <details class="card" open>
                    <summary class="card__header h4">
                        {{ __('waterhole::admin.page-details-title') }}
                    </summary>

                    <div class="card__body form-groups">
                        <x-waterhole::field
                            name="name"
                            :label="__('waterhole::admin.page-name-label')"
                        >
                            <input
                                type="text"
                                name="name"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('name', $page->name ?? null) }}"
                                autofocus
                                data-action="slugger#updateName"
                            >
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="slug"
                            :label="__('waterhole::admin.page-slug-label')"
                        >
                            <input
                                type="text"
                                name="slug"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('slug', $page->slug ?? null) }}"
                                data-action="slugger#updateSlug"
                                data-slugger-target="slug"
                            >
                            <p class="field__description">
                                {{ __('waterhole::admin.page-slug-url-label') }}
                                {!! preg_replace('~^https?://~', '', str_replace('*', '<span data-slugger-target="mirror">'.old('slug', $page->slug ?? '').'</span>', route('waterhole.page', ['page' => '*']))) !!}
                            </p>
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="icon"
                            :label="__('waterhole::admin.page-icon-label')"
                        >
                            <x-waterhole::admin.icon-picker
                                name="icon"
                                :value="old('icon', $page->icon ?? null)"
                            />
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="body"
                            :label="__('waterhole::admin.page-body-label')"
                        >
                            <x-waterhole::text-editor
                                name="body"
                                id="{{ $component->id }}"
                                :value="old('body', $page->body ?? null)"
                                class="input"
                            />
                        </x-waterhole::field>
                    </div>
                </details>

                <details class="card">
                    <summary class="card__header h4">
                        {{ __('waterhole::admin.page-permissions-title') }}
                    </summary>

                    <div class="card__body">
                        <x-waterhole::admin.permission-grid
                            :abilities="['view']"
                            :defaults="['view']"
                            :permissions="$page->permissions ?? null"
                        />
                    </div>
                </details>
            </div>

            <div class="row gap-md">
                <button
                    type="submit"
                    class="btn btn--primary btn--wide"
                >
                    {{ isset($heading) ? __('waterhole::system.save-changes-button') : __('waterhole::system.create-button') }}
                </button>

                <a
                    href="{{ route('waterhole.admin.structure') }}"
                    class="btn"
                >{{ __('waterhole::system.cancel-button') }}</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
