<li
    class="admin-structure__node"
    data-id="{{ $node->id }}"
    draggable="true"
>
    <div class="admin-structure__content toolbar">
        <x-waterhole::icon icon="heroicon-o-menu" class="color-muted admin-structure__handle js-only" data-handle/>

        @switch ($node->type)
            @case('channel')
                <x-waterhole::channel-label :channel="$node->content" class="admin-structure__label" link target="_blank"/>
                <span class="with-icon text-xs color-muted">
                    <x-waterhole::icon icon="heroicon-o-chat-alt-2"/>
                    <span>Channel</span>
                </span>
                @break

            @case('link')
                <a href="{{ $node->data['href'] }}" class="admin-structure__label with-icon color-text" target="_blank">
                    <x-waterhole::icon :icon="$node->data['icon'] ?? null"/>
                    <span>{{ $node->data['label'] ?? 'Group' }}</span>
                </a>
                <span class="with-icon text-xs color-muted">
                    <x-waterhole::icon icon="heroicon-s-link"/>
                    <span>Link</span>
                </span>
                @break

            @case('group')
                <span class="admin-structure__label color-muted">
                    {{ $node->data['label'] ?? 'Group' }}
                </span>
        @endswitch

        <div class="spacer"></div>

        <x-waterhole::action-menu
            :for="$node->content ?: $node"
            context="admin"
            placement="bottom-end"
        />
    </div>

    @if ($node->type === 'group')
        <ul role="list" class="admin-structure__children admin-structure__nodes">
            @foreach ($node->children as $child)
                <x-waterhole::admin.structure-node :node="$child"/>
            @endforeach
        </ul>
    @endif
</li>
