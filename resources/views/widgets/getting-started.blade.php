<div class="card stack-md">
    <h2 class="h3">Get Started With Waterhole</h2>

    <div class="grid" style="--grid-min: 30ch; gap: 0">
        <a href="#" class="cluster-md block-link">
            <x-waterhole::icon icon="heroicon-o-map" class="text-xl no-shrink icon--thin"/>
            <div class="stack-xs">
                <div class="h4 color-accent">Develop Your Strategy</div>
                <div class="color-muted text-xs">Learn how to build a successful community with Waterhole.</div>
            </div>
        </a>

        <a href="{{ route('waterhole.admin.structure') }}" class="cluster-md block-link">
            <x-waterhole::icon icon="heroicon-o-collection" class="text-xl no-shrink icon--thin"/>
            <div class="stack-xs">
                <div class="h4 color-accent">Set Up Your Structure</div>
                <div class="color-muted text-xs">Configure the channels and pages that make up the skeleton of your community.</div>
            </div>
        </a>

        <a href="{{ route('waterhole.admin.groups.index') }}" class="cluster-md block-link">
            <x-waterhole::icon icon="heroicon-o-user-group" class="text-xl no-shrink icon--thin"/>
            <div class="stack-xs">
                <div class="h4 color-accent">Define User Groups</div>
                <div class="color-muted text-xs">Set up groups for moderators, staff, and superusers.</div>
            </div>
        </a>

        <a href="#" class="cluster-md block-link">
            <x-waterhole::icon icon="heroicon-o-color-swatch" class="text-xl no-shrink icon--thin"/>
            <div class="stack-xs">
                <div class="h4 color-accent">Customize The Design</div>
                <div class="color-muted text-xs">Learn how to integrate Waterhole with your brand and make it your own.</div>
            </div>
        </a>
    </div>
</div>
