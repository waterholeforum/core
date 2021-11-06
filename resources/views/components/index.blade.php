<section class="hero">
    <div class="container">
        <h1>Community</h1>
        <p class="lead">
            Welcome to the Waterhole Community, a place to ask questions,
            discuss ideas, and share community management tips.
        </p>
        <form action="{{ route('waterhole.search') }}" class="lead">
            <div class="input-container full-width search-input">
                <x-waterhole::icon
                    icon="heroicon-o-search"
                    class="pointer-events-none"
                />
                <input
                    class="input"
                    style="border-radius: 999px;"
                    type="text"
                    name="q"
                    value="{{ request('q') }}"
                    placeholder="{{ __('waterhole::forum.search-placeholder') }}"
                    required
                >
                <span>
                    <button class="btn btn--icon btn--link">
                        <x-waterhole::icon icon="heroicon-o-arrow-right"/>
                    </button>
                </span>
            </div>
        </form>
    </div>
</section>

<div class="container with-sidebar-start index-layout">
    <div class="sidebar--sticky">
        @components(Waterhole\Extend\IndexNav::getComponents())
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
