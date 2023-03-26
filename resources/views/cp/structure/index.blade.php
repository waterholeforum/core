<x-waterhole::cp :title="__('waterhole::cp.structure-title')">
    <turbo-frame id="structure" target="_top">
        <x-waterhole::cp.sortable-context
            class="stack gap-md"
            data-controller="form"
            data-action="sortable:update->form#submit"
        >
            <div class="row gap-xs">
                <h1 class="h3">{{ __('waterhole::cp.structure-title') }}</h1>

                <div class="grow"></div>

                <ui-popup placement="bottom-end">
                    <button type="button" class="btn bg-accent">
                        <x-waterhole::icon icon="tabler-plus"/>
                        <span>{{ __('waterhole::system.create-button') }}</span>
                        <x-waterhole::icon icon="tabler-chevron-down"/>
                    </button>

                    <ui-menu class="menu" hidden>
                        <a
                            href="{{ route('waterhole.cp.structure.channels.create') }}"
                            type="button"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-message-circle-2"/>
                            <span>{{ __('waterhole::cp.structure-channel-label') }}</span>
                        </a>
                        <a
                            href="{{ route('waterhole.cp.structure.pages.create') }}"
                            type="button"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-file-text"/>
                            <span>{{ __('waterhole::cp.structure-page-label') }}</span>
                        </a>
                        <a
                            href="{{ route('waterhole.cp.structure.links.create') }}"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-link"/>
                            <span>{{ __('waterhole::cp.structure-link-label') }}</span>
                        </a>
                        <a
                            href="{{ route('waterhole.cp.structure.headings.create') }}"
                            class="menu-item"
                            role="menuitem"
                        >
                            <x-waterhole::icon icon="tabler-hash"/>
                            <span>{{ __('waterhole::cp.structure-heading-label') }}</span>
                        </a>
                    </ui-menu>
                </ui-popup>
            </div>

            <ul
                class="card sortable"
                role="list"
                aria-label="{{ __('waterhole::cp.structure-navigation-title') }}"
                data-sortable-target="container"
            >
                @foreach ($structure->where('is_listed', true) as $node)
                    <x-waterhole::cp.structure-node :node="$node"/>
                @endforeach

                <li class="placeholder hide-if-not-only-child">
                    {{ __('waterhole::cp.structure-navigation-description') }}
                </li>
            </ul>

            <div class="stack gap-md" style="margin-top: var(--space-xl)">
                <h2 class="h4">{{ __('waterhole::cp.structure-unlisted-title') }}</h2>

                <ul
                    class="card sortable"
                    role="list"
                    aria-label="{{ __('waterhole::cp.structure-unlisted-title') }}"
                    data-sortable-target="container"
                >
                    @foreach ($structure->where('is_listed', false) as $node)
                        <x-waterhole::cp.structure-node :node="$node"/>
                    @endforeach

                    <li class="placeholder hide-if-not-only-child">
                        {{ __('waterhole::cp.structure-unlisted-description') }}
                    </li>
                </ul>
            </div>

            <form
                action="{{ route('waterhole.cp.structure') }}"
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
        </x-waterhole::cp.sortable-context>
    </turbo-frame>
</x-waterhole::cp>
