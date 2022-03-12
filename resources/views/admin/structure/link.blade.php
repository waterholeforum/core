@php
    $title = isset($link)
        ? __('waterhole::admin.edit-link-title')
        : __('waterhole::admin.create-link-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        :parent-title="__('waterhole::admin.structure-title')"
        :title="$title"
    />

    <form
        method="POST"
        action="{{ isset($link) ? route('waterhole.admin.structure.links.update', compact('link')) : route('waterhole.admin.structure.links.store') }}"
        enctype="multipart/form-data"
    >
        @csrf
        @if (isset($link)) @method('PATCH') @endif

        <div class="stack gap-lg">
            <x-waterhole::validation-errors/>

            <div class="stack gap-md">
                <details class="card" open>
                    <summary class="card__header h4">
                        {{ __('waterhole::admin.link-details-title') }}
                    </summary>

                    <div class="card__body form">
                        <x-waterhole::field
                            name="name"
                            :label="__('waterhole::admin.link-name-label')"
                        >
                            <input
                                type="text"
                                name="name"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('name', $link->name ?? null) }}"
                                autofocus
                            >
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="icon"
                            :label="__('waterhole::admin.link-icon-label')"
                        >
                            <x-waterhole::admin.icon-picker
                                name="icon"
                                :value="old('icon', $link->icon ?? null)"
                            />
                        </x-waterhole::field>

                        <x-waterhole::field
                            name="href"
                            :label="__('waterhole::admin.link-url-label')"
                        >
                            <input
                                type="text"
                                name="href"
                                id="{{ $component->id }}"
                                class="input"
                                value="{{ old('href', $link->href ?? null) }}"
                            >
                        </x-waterhole::field>
                    </div>
                </details>

                <details class="card">
                    <summary class="card__header h4">
                        {{ __('waterhole::admin.link-permissions-title') }}
                    </summary>

                    <div class="card__body">
                        <x-waterhole::admin.permission-grid
                            :abilities="['view']"
                            :defaults="['view']"
                            :permissions="$link->permissions ?? null"
                        />
                    </div>
                </details>
            </div>

            <div class="row gap-xs wrap">
                <button
                    type="submit"
                    class="btn bg-accent btn--wide"
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
