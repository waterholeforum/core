<x-waterhole::admin title="Structure">
    <div class="stack-md" data-controller="admin-structure dragon-nest">
        <x-waterhole::alert type="info" class="alert--xl" icon="heroicon-o-collection">
            <div class="content">
                <h3>Giving your forum structure</h3>
                <p>Lorem ipsum dolor sit amet, has choro debitis ne, debet harum quando ex his. Cu elit noster usu, atqui mucius eum no. Et vix stet purto, quem propriae eam ne. Mea verear sapientem ea, ut laudem apeirian sit. Pro placerat oporteat ex.</p>
            </div>
        </x-waterhole::alert>

        <div class="toolbar toolbar--right">
            <form action="{{ route('waterhole.admin.structure') }}" method="post" data-admin-structure-target="orderForm" hidden>
                @csrf
                <input type="hidden" name="order" data-dragon-nest-target="orderInput">
                <button type="submit" class="btn is-active">Save Order</button>
            </form>

            <ui-popup placement="bottom-end">
                <button type="button" class="btn btn--primary">
                    <x-waterhole::icon icon="heroicon-s-plus"/>
                    <span>Create</span>
                    <x-waterhole::icon icon="heroicon-s-chevron-down"/>
                </button>

                <ui-menu class="menu">
                    <a href="{{ route('waterhole.admin.structure.channels.create') }}" type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-chat-alt-2"/>
                        <span>Channel</span>
                    </a>
                    <button type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-document-text"/>
                        <span>Page</span>
                    </button>
                    <button type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-link"/>
                        <span>Link</span>
                    </button>
                    <button type="button" class="menu-item" role="menuitem">
                        <x-waterhole::icon icon="heroicon-o-folder"/>
                        <span>Group</span>
                    </button>
                </ui-menu>
            </ui-popup>
        </div>

        <ul
            class="card admin-structure"
            role="list"
            data-dragon-nest-target="list"
            data-action="dragend->admin-structure#showOrderForm"
        >
            @foreach ($structure as $node)
                <x-waterhole::admin.structure-node :node="$node"/>
            @endforeach
        </ul>
    </div>
</x-waterhole::admin>
