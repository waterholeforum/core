<nav
    aria-label="{{ __('waterhole::forum.structure-breadcrumbs-label') }}"
    itemscope
    itemtype="https://schema.org/BreadcrumbList"
>
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $crumb)
            <li
                @if ($loop->last) aria-current="location" @endif
                itemprop="itemListElement"
                itemscope
                itemtype="https://schema.org/ListItem"
            >
                @if ($loop->last)
                    <x-waterhole::channel-label
                        :channel="$post->channel"
                        itemprop="item"
                        link
                    />
                    <meta
                        itemprop="name"
                        content="{{ $post->channel->name }}"
                    />
                @else
                    <a itemprop="item" href="{{ $crumb->content->url }}">
                        <span itemprop="name">
                            {{ $crumb->content->name }}
                        </span>
                    </a>
                @endif
                <meta itemprop="position" content="{{ $loop->iteration }}" />
            </li>
        @endforeach
    </ol>
</nav>
