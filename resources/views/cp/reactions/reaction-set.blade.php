@php
    $title = isset($reactionSet)
        ? __('waterhole::cp.edit-reaction-set-title')
        : __('waterhole::cp.create-reaction-set-title');
@endphp

<x-waterhole::cp :title="$title">
    <x-waterhole::cp.title
        :parent-url="route('waterhole.cp.reaction-sets.index')"
        :parent-title="__('waterhole::cp.reactions-title')"
        :title="$title"
    />

    <div class="stack gap-md">
        <x-waterhole::form
            :fields="$form->fields()"
            :method="isset($reactionSet) ? 'PATCH' : 'POST'"
            :submit-label="isset($reactionSet) ? null : __('waterhole::system.continue-button')"
            action="{{
                isset($reactionSet)
                    ? route('waterhole.cp.reaction-sets.update', compact('reactionSet'))
                    : route('waterhole.cp.reaction-sets.store')
            }}"
            enctype="multipart/form-data"
            data-controller="dirty-form"
        />
    </div>
</x-waterhole::cp>
