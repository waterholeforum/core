<nav
    {{ $attributes->class('structure-children stack gap-lg') }}
    aria-label="{{ __('waterhole::forum.structure-children-label') }}"
>
    @foreach ($groups as $group)
        <div class="stack gap-sm">
            @if ($group['heading'])
                <h2 class="subtitle color-muted">
                    {{ $group['heading']->name }}
                </h2>
            @endif

            <div class="structure-children__items gap-md grid">
                @foreach ($group['items'] as $node)
                    <x-waterhole::structure-card :content="$node->content" />
                @endforeach
            </div>
        </div>
    @endforeach
</nav>
