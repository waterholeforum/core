@props(['breadcrumb' => null])

<header class="header" role="banner">
    <div class="container">
        @components(Waterhole\Extend\SiteHeader::getComponents(), compact('breadcrumb'))
    </div>
</header>
