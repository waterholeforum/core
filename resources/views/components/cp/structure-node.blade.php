<li
    class="card__row cp-structure__node"
    data-id="{{ $node->id }}"
    data-content-type="{{ $node->content->getMorphClass() }}"
    aria-labelledby="label_{{ $node->id }}"
>
    <div class="cp-structure__content row gap-md">
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
            <span class="with-icon text-xs color-muted hide-sm">
                @icon('tabler-message-circle-2')
                <span>{{ __('waterhole::cp.structure-channel-label') }}</span>
            </span>

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
            <span class="with-icon text-xs color-muted hide-sm">
                @icon('tabler-file-text')
                <span>{{ __('waterhole::cp.structure-page-label') }}</span>
            </span>

        @elseif ($node->content instanceof Waterhole\Models\StructureHeading)
            <span class="cp-structure__label color-muted" id="label_{{ $node->id }}">
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
            <span class="with-icon text-xs color-muted hide-sm">
                @icon('tabler-link')
                <span>{{ __('waterhole::cp.structure-link-label') }}</span>
            </span>
        @endif

        <div class="grow"></div>

        @if (
            method_exists($node->content, 'permissions')
            && $recipients = Waterhole::permissions()
                ->scope($node->content)
                ->where('ability', 'view')
                ->load('recipient')
                ->filter(fn($permission) => $permission->recipient instanceof Waterhole\Models\Group)
                ->map->recipient
        )
            @if ($recipients->contains(Waterhole\Models\Group::GUEST_ID))
                <span class="with-icon text-xs color-muted hide-sm">
                    @icon('tabler-world')
                    {{ __('waterhole::cp.structure-visibility-public-label') }}
                </span>
            @elseif ($recipients->contains(Waterhole\Models\Group::MEMBER_ID))
                <span class="with-icon text-xs color-muted hide-sm">
                    @icon('tabler-user')
                    {{ Waterhole\Models\Group::member()->name }}
                </span>
            @else
                <span class="hide-sm">
                    @forelse ($recipients as $group)
                        <x-waterhole::group-badge :group="$group"/>
                    @empty
                        <x-waterhole::group-badge :group="Waterhole\Models\Group::admin()"/>
                    @endforelse
                </span>
            @endif
        @endif

        <x-waterhole::action-buttons
            class="row text-xs"
            :for="$node->content"
            :button-attributes="['class' => 'btn btn--icon btn--transparent']"
            tooltips
            :limit="2"
            placement="bottom-end"
        />
    </div>
</li>
