<section class="hero">
    <div class="container">
        <h1>Community</h1>
        <p class="lead">
            Welcome to the Waterhole Community, a place to ask questions,
            discuss ideas, and share community management tips.
        </p>
        <form method="get" class="lead">
            <x-waterhole::search-input :placeholder="__('waterhole::forum.search-placeholder')"/>
        </form>
    </div>
</section>

<div class="container with-sidebar-start index-layout">
    <div>
        @components(Waterhole\Extend\IndexNav::getComponents())
    </div>

    <main id="main">
        {{ $slot }}
    </main>
</div>
