<x-waterhole::admin title="Create a Channel">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        parent-title="Structure"
        title="Create a Channel"
    />

    <form
        method="POST"
        action="{{ route('waterhole.admin.structure.channels.store') }}"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="stack-lg">
            <x-waterhole::validation-errors/>

            @include('waterhole::admin.structure.channels.fields')

            <div class="toolbar">
                <button type="submit" class="btn btn--primary btn--wide">Create</button>
                <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
