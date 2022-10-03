<li
    class="card__row admin-structure__node"
    data-id="{{ $node->id }}"
    data-content-type="{{ $node->content->getMorphClass() }}"
    aria-labelledby="label_{{ $node->id }}"
>
    <div class="admin-structure__content row gap-xs">
        <button type="button" class="drag-handle" data-handle>
            <x-waterhole::icon icon="tabler-menu-2"/>
        </button>

        @if ($node->content instanceof Waterhole\Models\Channel)
            <x-waterhole::channel-label
                :channel="$node->content"
                class="admin-structure__label"
                link
                target="_blank"
                id="label_{{ $node->id }}"
            />
            <span class="with-icon text-xs color-muted hide-xs">
                <x-waterhole::icon icon="tabler-message-circle-2"/>
                <span>{{ __('waterhole::admin.structure-channel-label') }}</span>
            </span>

        @elseif ($node->content instanceof Waterhole\Models\Page)
            <a
                href="{{ $node->content->url }}"
                class="admin-structure__label with-icon color-text"
                target="_blank"
                id="label_{{ $node->id }}"
            >
                <x-waterhole::icon :icon="$node->content->icon ?? null"/>
                <span>{{ $node->content->name ?? 'Page' }}</span>
            </a>
            <span class="with-icon text-xs color-muted hide-xs">
                <x-waterhole::icon icon="tabler-file-text"/>
                <span>{{ __('waterhole::admin.structure-page-label') }}</span>
            </span>

        @elseif ($node->content instanceof Waterhole\Models\StructureHeading)
            <span class="admin-structure__label color-muted" id="label_{{ $node->id }}">
                {{ $node->content->name ?? __('waterhole::admin.structure-heading-label') }}
            </span>

        @elseif ($node->content instanceof Waterhole\Models\StructureLink)
            <a
                href="{{ $node->content->href }}"
                class="admin-structure__label with-icon color-text"
                target="_blank"
                id="label_{{ $node->id }}"
            >
                <x-waterhole::icon :icon="$node->content->icon ?? null"/>
                <span>{{ $node->content->name ?? 'Link' }}</span>
            </a>
            <span class="with-icon text-xs color-muted hide-xs">
                <x-waterhole::icon icon="tabler-link"/>
                <span>{{ __('waterhole::admin.structure-link-label') }}</span>
            </span>
        @endif

        <div class="grow"></div>

        @if (
            method_exists($node->content, 'permissions')
            && $permissions = app('waterhole.permissions')->load('recipient')->scope($node->content)
        )
            @if ($permissions->guest()->allows('view'))
                <span class="with-icon text-xs color-muted">
                    <x-waterhole::icon icon="tabler-world"/>
                    {{ __('waterhole::admin.structure-visibility-public-label') }}
                </span>
            @elseif ($permissions->member()->allows('view'))
                <span class="with-icon text-xs color-muted">
                    <x-waterhole::icon icon="tabler-user"/>
                    {{ Waterhole\Models\Group::member()->name }}
                </span>
            @else
                <span>
                    @forelse ($permissions->ability('view')->groups()->map->recipient as $group)
                        <x-waterhole::group-label :group="$group"/>
                    @empty
                        <x-waterhole::group-label :group="Waterhole\Models\Group::admin()"/>
                    @endforelse
                </span>
            @endif
        @endif

        <x-waterhole::action-menu
            :for="$node->content"
            context="admin"
            placement="bottom-end"
        />
    </div>
</li>
