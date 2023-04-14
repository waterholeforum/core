@php
    $title = isset($reactionSet)
        ? __('waterhole::cp.edit-reaction-set-title')
        : __('waterhole::cp.create-reaction-set-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.reaction-sets.index')"
        :parent-title="__('waterhole::cp.reactions-title')"
        :title="$title"
    />

    <div class="stack gap-md">
        <x-waterhole::validation-errors/>

        <details class="card" open>
            <summary class="card__header h5">Details</summary>
            <form
                method="POST"
                action="{{ isset($reactionSet)
                ? route('waterhole.cp.reaction-sets.update', compact('reactionSet'))
                : route('waterhole.cp.reaction-sets.store') }}"
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
                            href="{{ route('waterhole.cp.reaction-sets.index') }}"
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
                        <x-waterhole::cp.sortable-context
                            data-controller="form"
                            data-action="sortable:update->form#submit"
                        >
                            <ul
                                class="card sortable"
                                role="list"
                                data-sortable-target="container"
                                aria-label="{{ __('waterhole::cp.reaction-set-reactions-label') }}"
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
                                            @icon('tabler-grip-vertical')
                                        </button>

                                        @icon($reactionType->icon)
                                        {{ $reactionType->name }}
                                        <span class="color-muted text-xs">{{ $reactionType->score > 0 ? '+' : '' }}{{ $reactionType->score }}</span>

                                        <x-waterhole::action-buttons
                                            class="row text-xs push-end -m-xxs"
                                            :for="$reactionType"
                                            placement="bottom-end"
                                            :button-attributes="['class' => 'btn btn--icon btn--transparent']"
                                            tooltips
                                            :limit="2"
                                        />
                                    </li>
                                @empty
                                    <li class="placeholder">No Reaction Types</li>
                                @endforelse
                            </ul>

                            <form
                                action="{{ route('waterhole.cp.reaction-sets.reaction-types.reorder', compact('reactionSet')) }}"
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
                        </x-waterhole::cp.sortable-context>

                        <div>
                            <a
                                href="{{ route('waterhole.cp.reaction-sets.reaction-types.create', compact('reactionSet')) }}"
                                class="btn"
                                data-turbo-frame="modal"
                            >
                                @icon('tabler-plus')
                                Add
                            </a>
                        </div>
                    </div>
                </turbo-frame>
            </details>
        @endisset
    </div>
</x-waterhole::cp>
