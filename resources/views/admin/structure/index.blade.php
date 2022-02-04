<x-waterhole::admin title="Structure">
    <div class="stack-md" data-controller="form dragon-nest">
{{--        <x-waterhole::alert type="info" class="alert--xl" icon="heroicon-o-collection">--}}
{{--            <div class="content">--}}
{{--                <h3>Giving your forum structure</h3>--}}
{{--                <p>Lorem ipsum dolor sit amet, has choro debitis ne, debet harum quando ex his. Cu elit noster usu, atqui mucius eum no. Et vix stet purto, quem propriae eam ne. Mea verear sapientem ea, ut laudem apeirian sit. Pro placerat oporteat ex.</p>--}}
{{--            </div>--}}
{{--        </x-waterhole::alert>--}}

        <div class="toolbar">
            <h1 class="h2">Structure</h1>

            <div class="spacer"></div>

            <ui-popup placement="bottom-end">
                <button type="button" class="btn btn--primary">
                    <x-waterhole::icon icon="heroicon-s-plus"/>
                    <span>Create</span>
                    <x-waterhole::icon icon="heroicon-s-chevron-down"/>
                </button>

                <ui-menu class="menu" hidden>
                    <a href="{{ route('waterhole.admin.structure.channels.create') }}" type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-chat-alt-2"/>
                        <span>Channel</span>
                    </a>
                    <a href="{{ route('waterhole.admin.structure.pages.create') }}" type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-document-text"/>
                        <span>Page</span>
                    </a>
                    <a href="{{ route('waterhole.admin.structure.links.create') }}" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-link"/>
                        <span>Link</span>
                    </a>
                    <a href="{{ route('waterhole.admin.structure.headings.create') }}" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-hashtag"/>
                        <span>Heading</span>
                    </a>
                </ui-menu>
            </ui-popup>
        </div>

        <ul
            class="card admin-structure"
            role="list"
            data-dragon-nest-target="list"
            data-action="dragend->form#submit"
        >
            @foreach ($structure->where('is_listed', true) as $node)
                <x-waterhole::admin.structure-node :node="$node"/>
            @endforeach
        </ul>

        <div class="stack-md" style="margin-top: 40px">
            <h2 class="h3">Unlisted</h2>
            <ul
                class="card admin-structure"
                role="list"
                data-dragon-nest-target="list"
                data-action="dragend->form#submit"
            >
                @foreach ($structure->where('is_listed', false) as $node)
                    <x-waterhole::admin.structure-node :node="$node"/>
                @endforeach
                <li class="placeholder">Move items here to hide them from the navigation menu.</li>
            </ul>
        </div>

        <form action="{{ route('waterhole.admin.structure') }}" method="post" data-form-target="form" hidden>
            @csrf
            <input type="hidden" name="order" data-dragon-nest-target="orderInput">
        </form>
    </div>
</x-waterhole::admin>
