@foreach ($nodes as $node)
    @if ($node->content instanceof Waterhole\Models\StructureHeading)
        <h4 class="menu-heading">{{ $node->content->name }}</h4>
    @elseif ($node->content instanceof Waterhole\Models\Channel &&
    in_array($node->content_id, $selectable))
        <x-waterhole::menu-item
            type="submit"
            :$name
            :value="$node->content->id"
            :$form
            :active="$node->content->id == $value"
            :label="$node->content->name"
            :description="$node->content->description_text"
            :icon="$node->content->icon"
            role=""
        />
        @if ($node->children->isNotEmpty())
            <div class="channel-picker__children">
                @include(
                    'waterhole::components.channel-picker-nodes',
                    ['nodes' => $node->children]
                )
            </div>
        @endif
    @elseif ($node->content instanceof Waterhole\Models\Channel ||
    $node->content instanceof Waterhole\Models\Page)
        <details
            class="channel-picker__branch"
            @if (in_array($node->id, $expanded)) open @endif
        >
            <summary class="menu-item">
                @icon($node->content->icon)
                <span>
                    <span class="menu-item__title">
                        {{ $node->content->name }}
                    </span>
                    @if (filled($node->content->description))
                        <span class="menu-item__description">
                            {{ $node->content->description_text }}
                        </span>
                    @endif
                </span>
                @icon('tabler-chevron-right', ['class' => 'push-end'])
            </summary>
            <div class="channel-picker__children">
                @include(
                    'waterhole::components.channel-picker-nodes',
                    ['nodes' => $node->children]
                )
            </div>
        </details>
    @elseif ($node->content instanceof Waterhole\Models\StructureLink)
        <x-waterhole::menu-item
            :icon="$node->content->icon"
            :href="$node->content->href"
            target="_blank"
            :label="$node->content->name"
            :description="$node->content->description_text"
        />
    @endif
@endforeach
