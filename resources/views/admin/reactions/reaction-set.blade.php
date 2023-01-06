@php
    $title = isset($reactionSet)
        ? __('waterhole::admin.edit-reaction-set-title')
        : __('waterhole::admin.create-reaction-set-title');
@endphp

<x-waterhole::admin :title="$title">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.reaction-sets.index')"
        :parent-title="__('waterhole::admin.reactions-title')"
        :title="$title"
    />

    <div class="stack gap-md">
        <x-waterhole::validation-errors/>

        <details class="card" open>
            <summary class="card__header h5">Details</summary>
            <form
                method="POST"
                action="{{ isset($reactionSet)
                ? route('waterhole.admin.reaction-sets.update', compact('reactionSet'))
                : route('waterhole.admin.reaction-sets.store') }}"
                enctype="multipart/form-data"
                class="card__body"
            >
                @csrf
                @if (isset($reactionSet))
                    @method('PATCH')
                @endif

                <div class="stack dividers">
                    @components($form->fields())

                    <div class="row gap-xs wrap">
                        <button
                            type="submit"
                            class="btn bg-accent btn--wide"
                        >
                            {{ isset($reactionSet)
                                ? __('waterhole::system.save-changes-button')
                                : __('waterhole::system.continue-button') }}
                        </button>

                        <a
                            href="{{ route('waterhole.admin.reaction-sets.index') }}"
                            class="btn"
                        >{{ __('waterhole::system.cancel-button') }}</a>
                    </div>
                </div>
            </form>
        </details>

        @isset($reactionSet)
            <details class="card" open>
                <summary class="card__header h5">Reaction Types</summary>
                <turbo-frame id="reaction-types">
                    <div class="card__body stack gap-md">
                        <x-waterhole::admin.sortable-context
                            data-controller="form"
                            data-action="sortable:update->form#submit"
                        >
                            <ul
                                class="card sortable"
                                role="list"
                                data-sortable-target="container"
                                aria-label="{{ __('waterhole::admin.reaction-set-reactions-label') }}"
                            >
                                @forelse ($reactionSet->reactionTypes->load('reactionSet') as $reactionType)
                                    <li
                                        class="card__row row gap-sm"
                                        aria-label="{{ $reactionType->name }}"
                                        data-id="{{ $reactionType->id }}"
                                    >
                                        <button
                                            type="button"
                                            class="drag-handle"
                                            data-handle
                                        >
                                            <x-waterhole::icon icon="tabler-menu-2"/>
                                        </button>

                                        <x-waterhole::icon :icon="$reactionType->icon"/>
                                        {{ $reactionType->name }}
                                        <span class="color-muted text-xs">{{ $reactionType->score > 0 ? '+' : '' }}{{ $reactionType->score }}</span>

                                        <x-waterhole::action-menu
                                            :for="$reactionType"
                                            placement="bottom-end"
                                            class="push-end"
                                        />
                                    </li>
                                @empty
                                    <li class="placeholder">No Reaction Types</li>
                                @endforelse
                            </ul>

                            <form
                                action="{{ route('waterhole.admin.reaction-sets.reaction-types.reorder', compact('reactionSet')) }}"
                                method="post"
                                data-form-target="form"
                                hidden
                            >
                                @csrf
                                <input
                                    type="hidden"
                                    name="order"
                                    data-sortable-target="orderInput"
                                >
                            </form>
                        </x-waterhole::admin.sortable-context>

                        <div>
                            <a
                                href="{{ route('waterhole.admin.reaction-sets.reaction-types.create', compact('reactionSet')) }}"
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
