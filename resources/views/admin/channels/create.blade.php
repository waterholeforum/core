<x-waterhole::admin title="Create a Channel">
    <x-waterhole::dialog title="Create a Channel">
        <form
            method="POST"
            action="{{ route('waterhole.admin.structure.channels.store') }}"
        >
            @csrf

            <div class="stack-lg">
                <x-waterhole::validation-errors/>

                <div class="form-groups" data-controller="slugger">
                    @include('waterhole::admin.channels.fields')

                    <div>
                        <div class="toolbar">
                            <button type="submit" class="btn btn--primary btn--wide">Create</button>
                            <a href="{{ route('waterhole.admin.structure') }}" class="btn">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </x-waterhole::dialog>
</x-waterhole::admin>
