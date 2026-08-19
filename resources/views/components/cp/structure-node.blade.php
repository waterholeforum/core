<li
    class="card cp-structure__node row gap-md"
    data-id="{{ $node->id }}"
    data-depth="{{ $node->depth }}"
    data-can-have-children="{{ $node->canHaveChildren() ? 1 : 0 }}"
    style="--structure-depth: {{ $node->depth }}"
    aria-labelledby="label_{{ $node->id }}"
>
    <button type="button" class="drag-handle" data-handle>
        @icon('tabler-grip-vertical')
    </button>

    @if ($node->content instanceof Waterhole\Models\Channel)
        <x-waterhole::channel-label
            :channel="$node->content"
            class="cp-structure__label"
            link
            target="_blank"
            id="label_{{ $node->id }}"
        />
    @elseif ($node->content instanceof Waterhole\Models\Page)
        <a
            href="{{ $node->content->url }}"
            class="cp-structure__label with-icon color-text"
            target="_blank"
            id="label_{{ $node->id }}"
        >
            @icon($node->content->icon ?? null)
            <span>{{ $node->content->name ?? 'Page' }}</span>
        </a>
    @elseif ($node->content instanceof Waterhole\Models\StructureHeading)
        <span
            class="cp-structure__label color-muted"
            id="label_{{ $node->id }}"
        >
            {{ $node->content->name ?? __('waterhole::cp.structure-heading-label') }}
        </span>
    @elseif ($node->content instanceof Waterhole\Models\StructureLink)
        <a
            href="{{ $node->content->href }}"
            class="cp-structure__label with-icon color-text"
            target="_blank"
            id="label_{{ $node->id }}"
        >
            @icon($node->content->icon ?? null)
            <span>{{ $node->content->name ?? 'Link' }}</span>
        </a>
    @endif

    @if ($node->content instanceof Waterhole\Models\Channel)
        <span class="with-icon text-xs color-muted hide-sm">
            @icon('tabler-message-circle-2')
            <span>{{ __('waterhole::cp.structure-channel-label') }}</span>
        </span>
    @elseif ($node->content instanceof Waterhole\Models\Page)
        <span class="with-icon text-xs color-muted hide-sm">
            @icon('tabler-file-text')
            <span>{{ __('waterhole::cp.structure-page-label') }}</span>
        </span>
    @elseif ($node->content instanceof Waterhole\Models\StructureLink)
        <span class="with-icon text-xs color-muted hide-sm">
            @icon('tabler-link')
            <span>{{ __('waterhole::cp.structure-link-label') }}</span>
        </span>
    @endif

    <div class="grow"></div>

    @unless ($node->is_listed)
        <span
            class="with-icon color-muted"
            aria-label="{{ __('waterhole::cp.structure-unlisted-label') }}"
        >
            @icon('tabler-eye-off')
            <ui-tooltip>
                {{ __('waterhole::cp.structure-unlisted-label') }}
            </ui-tooltip>
        </span>
    @endunless

    @unless ($node->content instanceof Waterhole\Models\StructureHeading)
        @if ($recipients->contains(Waterhole\Models\Group::GUEST_ID))
            <span class="with-icon text-xs color-muted hide-sm">
                @icon('tabler-world')
                {{ __('waterhole::cp.structure-visibility-public-label') }}
            </span>
        @elseif ($recipients->contains(Waterhole\Models\Group::MEMBER_ID))
            <span class="with-icon text-xs color-muted hide-sm">
                @icon('tabler-user')
                {{ __('waterhole::cp.structure-visibility-members-label') }}
            </span>
        @else
            <span class="hide-sm">
                @forelse ($recipients as $group)
                    <x-waterhole::group-badge :group="$group" />
                @empty
                    <x-waterhole::group-badge
                        :group="Waterhole\Models\Group::admin()"
                    />
                @endforelse
            </span>
        @endif
    @endunless

    <div class="row text-xs">
        @if ($node->canHaveChildren())
            <x-waterhole::cp.structure-create-menu :parent="$node">
                <button
                    type="button"
                    class="btn btn--transparent btn--icon"
                    aria-label="{{ __('waterhole::cp.structure-create-child-button') }}"
                >
                    @icon('tabler-circle-plus')
                    <ui-tooltip>
                        {{ __('waterhole::cp.structure-create-child-button') }}
                    </ui-tooltip>
                </button>
            </x-waterhole::cp.structure-create-menu>
        @endif

        <x-waterhole::action-buttons
            :for="$node->content"
            context="cp"
            :limit="2"
        />
    </div>
</li>
