<x-waterhole::admin title="Edit Channel">
    <x-waterhole::dialog title="Edit Channel">
        <form
            method="POST"
            action="{{ route('waterhole.admin.structure.channels.update', compact('channel')) }}"
        >
            @csrf
            @method('PATCH')

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

                <div class="form-groups" data-controller="slugger">
                    @include('waterhole::admin.channels.fields')

                    <div>
                        <div class="toolbar">
                            <button type="submit" class="btn btn--primary btn--wide">Save Changes</button>
                            <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
