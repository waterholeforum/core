<x-waterhole::cp-layout :title="__('waterhole::cp.structure-title')">
    <div
        class="stack gap-md"
        data-controller="sortable form"
        data-action="sortable:update->form#submit"
    >
        <div class="row gap-xs">
            <h1 class="h3">{{ __('waterhole::cp.structure-title') }}</h1>

            <div class="grow"></div>

            <x-waterhole::cp.structure-create-menu />
        </div>

        <ul
            class="cp-structure sortable stack gap-xxs"
            role="list"
            aria-label="{{ __('waterhole::cp.structure-title') }}"
            data-sortable-target="container"
        >
            @foreach ($structure as $node)
                <x-waterhole::cp.structure-node :node="$node" />
            @endforeach
        </ul>

        <turbo-frame id="structure_form" hidden>
            <form
                action="{{ route('waterhole.cp.structure') }}"
                method="post"
                data-form-target="form"
            >
                @csrf
                <input
                    type="hidden"
                    name="order"
                    data-sortable-target="orderInput"
                />
            </form>
        </turbo-frame>
    </div>
</x-waterhole::cp-layout>
