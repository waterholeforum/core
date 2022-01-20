<x-waterhole::admin title="Edit Channel">
    <x-waterhole::admin.title
        :parent-url="route('waterhole.admin.structure')"
        parent-title="Structure"
        title="Edit Channel"
    />

    <form
        method="POST"
        action="{{ route('waterhole.admin.structure.channels.update', compact('channel')) }}"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PATCH')

        <div class="stack-sm">
            <x-waterhole::validation-errors/>

            @include('waterhole::admin.structure.channels.fields')

            <div class="toolbar">
                <button type="submit" class="btn btn--primary btn--wide">Save Changes</button>
                <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
            </div>
        </div>
    </form>
</x-waterhole::admin>
