<x-waterhole::admin :title="__('waterhole::admin.structure-title')">
    <turbo-frame id="structure" target="_top">
        <x-waterhole::admin.sortable-context
            class="stack gap-md"
            data-controller="form"
            data-action="sortable:update->form#submit"
        >
            <div class="row gap-xs">
                <h1 class="h3">{{ __('waterhole::admin.structure-title') }}</h1>

                <div class="grow"></div>

                <ui-popup placement="bottom-end">
                    <button type="button" class="btn bg-accent">
                        <x-waterhole::icon icon="tabler-plus"/>
                        <span>{{ __('waterhole::system.create-button') }}</span>
                        <x-waterhole::icon icon="tabler-chevron-down"/>
                    </button>

                    <ui-menu class="menu" hidden>
                        <a
                            href="{{ route('waterhole.admin.structure.channels.create') }}"
                            type="button"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-message-circle-2"/>
                            <span>{{ __('waterhole::admin.structure-channel-label') }}</span>
                        </a>
                        <a
                            href="{{ route('waterhole.admin.structure.pages.create') }}"
                            type="button"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-file-text"/>
                            <span>{{ __('waterhole::admin.structure-page-label') }}</span>
                        </a>
                        <a
                            href="{{ route('waterhole.admin.structure.links.create') }}"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-link"/>
                            <span>{{ __('waterhole::admin.structure-link-label') }}</span>
                        </a>
                        <a
                            href="{{ route('waterhole.admin.structure.headings.create') }}"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-hash"/>
                            <span>{{ __('waterhole::admin.structure-heading-label') }}</span>
                        </a>
                    </ui-menu>
                </ui-popup>
            </div>

            <ul
                class="card sortable"
                role="list"
                aria-label="{{ __('waterhole::admin.structure-navigation-title') }}"
                data-sortable-target="container"
            >
                @foreach ($structure->where('is_listed', true) as $node)
                    <x-waterhole::admin.structure-node :node="$node"/>
                @endforeach

                <li class="placeholder hide-if-not-only-child">
                    {{ __('waterhole::admin.structure-navigation-description') }}
                </li>
            </ul>

            <div class="stack gap-md" style="margin-top: var(--space-xl)">
                <h2 class="h4">{{ __('waterhole::admin.structure-unlisted-title') }}</h2>

                <ul
                    class="card sortable"
                    role="list"
                    aria-label="{{ __('waterhole::admin.structure-unlisted-title') }}"
                    data-sortable-target="container"
                >
                    @foreach ($structure->where('is_listed', false) as $node)
                        <x-waterhole::admin.structure-node :node="$node"/>
                    @endforeach

                    <li class="placeholder hide-if-not-only-child">
                        {{ __('waterhole::admin.structure-unlisted-description') }}
                    </li>
                </ul>
            </div>

            <form
                action="{{ route('waterhole.admin.structure') }}"
                method="post"
                data-turbo-frame="structure"
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
    </turbo-frame>
</x-waterhole::admin>
