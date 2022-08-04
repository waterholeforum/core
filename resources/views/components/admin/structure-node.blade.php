<li
    class="card__row admin-structure__node"
    data-id="{{ $node->id }}"
    data-content-type="{{ $node->content->getMorphClass() }}"
    draggable="true"
>
    <div class="admin-structure__content row gap-xs wrap">
        <x-waterhole::icon icon="heroicon-o-menu" class="color-muted admin-structure__handle js-only" data-handle/>

        @if ($node->content instanceof Waterhole\Models\Channel)
            <x-waterhole::channel-label :channel="$node->content" class="admin-structure__label" link target="_blank"/>
            <span class="with-icon text-xs color-muted">
                <x-waterhole::icon icon="heroicon-o-chat-alt-2"/>
                <span>Channel</span>
            </span>

        @elseif ($node->content instanceof Waterhole\Models\Page)
            <a href="{{ $node->content->url }}" class="admin-structure__label with-icon color-text" target="_blank">
                <x-waterhole::icon :icon="$node->content->icon ?? null"/>
                <span>{{ $node->content->name ?? 'Page' }}</span>
            </a>
            <span class="with-icon text-xs color-muted">
                <x-waterhole::icon icon="heroicon-o-document-text"/>
                <span>Page</span>
            </span>

        @elseif ($node->content instanceof Waterhole\Models\StructureHeading)
            <span class="admin-structure__label color-muted">
                {{ $node->content->name ?? 'Heading' }}
            </span>

        @elseif ($node->content instanceof Waterhole\Models\StructureLink)
            <a href="{{ $node->content->href }}" class="admin-structure__label with-icon color-text" target="_blank">
                <x-waterhole::icon :icon="$node->content->icon ?? null"/>
                <span>{{ $node->content->name ?? 'Link' }}</span>
            </a>
            <span class="with-icon text-xs color-muted">
                <x-waterhole::icon icon="heroicon-s-link"/>
                <span>Link</span>
            </span>
        @endif

        <div class="grow"></div>

        @if ($permissions = app('waterhole.permissions')->load('recipient')->scope($node->content))
            @if ($permissions->guest()->allows('view'))
                <span class="with-icon text-xs color-muted">
                    <x-waterhole::icon icon="heroicon-o-globe"/>
                    Public
                </span>
            @elseif ($permissions->member()->allows('view'))
                <span class="with-icon text-xs color-muted">
                    <x-waterhole::icon icon="heroicon-o-user"/>
                    Member
                </span>
            @else
                <span>
                    @forelse ($permissions->ability('view')->groups()->map->recipient as $group)
                        <x-waterhole::group-label :group="$group"/>
                    @empty
                        <span class="badge">Admin</span>
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
