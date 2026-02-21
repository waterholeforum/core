@php
    $title = isset($taxonomy)
        ? __('waterhole::cp.edit-taxonomy-title')
        : __('waterhole::cp.create-taxonomy-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.taxonomies.index')"
        :parent-title="__('waterhole::cp.taxonomies-title')"
        :title="$title"
    />

    <div class="stack gap-xl">
        <x-waterhole::form
            :fields="$form->fields()"
            :method="isset($taxonomy) ? 'PATCH' : 'POST'"
            :submit-label="isset($taxonomy) ? null : __('waterhole::system.continue-button')"
            action="{{
                isset($taxonomy)
                    ? route('waterhole.cp.taxonomies.update', compact('taxonomy'))
                    : route('waterhole.cp.taxonomies.store')
            }}"
            enctype="multipart/form-data"
            data-controller="dirty-form"
        />
    </div>
</x-waterhole::cp>
