<x-waterhole::admin :title="__('waterhole::admin.structure-title')">
    <div
        class="stack gap-md"
        data-controller="form dragon-nest"
        data-action="dragend->form#submit"
    >
        <div class="row gap-xs">
            <h1 class="h2">{{ __('waterhole::admin.structure-title') }}</h1>

            <div class="spacer"></div>

            <ui-popup placement="bottom-end">
                <button type="button" class="btn btn--primary">
                    <x-waterhole::icon icon="heroicon-s-plus"/>
                    <span>{{ __('waterhole::system.create-button') }}</span>
                    <x-waterhole::icon icon="heroicon-s-chevron-down"/>
                </button>

                <ui-menu class="menu" hidden>
                    <a href="{{ route('waterhole.admin.structure.channels.create') }}" type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-chat-alt-2"/>
                        <span>{{ __('waterhole::admin.structure-channel-label') }}</span>
                    </a>
                    <a href="{{ route('waterhole.admin.structure.pages.create') }}" type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-document-text"/>
                        <span>{{ __('waterhole::admin.structure-page-label') }}</span>
                    </a>
                    <a href="{{ route('waterhole.admin.structure.links.create') }}" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-link"/>
                        <span>{{ __('waterhole::admin.structure-link-label') }}</span>
                    </a>
                    <a href="{{ route('waterhole.admin.structure.headings.create') }}" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-hashtag"/>
                        <span>{{ __('waterhole::admin.structure-heading-label') }}</span>
                    </a>
                </ui-menu>
            </ui-popup>
        </div>

        <ul
            class="card"
            role="list"
            data-dragon-nest-target="list"
        >
            @foreach ($structure->where('is_listed', true) as $node)
                <x-waterhole::admin.structure-node :node="$node"/>
            @endforeach
        </ul>

        <div class="stack gap-md" style="margin-top: var(--space-xxl)">
            <h2 class="h3">{{ __('waterhole::admin.structure-unlisted-title') }}</h2>

            <ul
                class="card"
                role="list"
                data-dragon-nest-target="list"
            >
                @foreach ($structure->where('is_listed', false) as $node)
                    <x-waterhole::admin.structure-node :node="$node"/>
                @endforeach

                <li class="placeholder">{{ __('waterhole::admin.structure-unlisted-description') }}</li>
            </ul>
        </div>

        <form action="{{ route('waterhole.admin.structure') }}" method="post" data-form-target="form" hidden>
            @csrf
            <input type="hidden" name="order" data-dragon-nest-target="orderInput">
        </form>
    </div>
</x-waterhole::admin>
