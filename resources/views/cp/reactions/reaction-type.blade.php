@php
    $title = isset($reactionType)
        ? __('waterhole::cp.edit-reaction-type-title')
        : __('waterhole::cp.create-reaction-type-title');
@endphp

<x-waterhole::cp :title="$title">
    <turbo-frame id="modal" data-modal-static>
        <x-waterhole::dialog :title="$title" class="dialog--sm">
            <x-waterhole::form
                :fields="$form->fields()"
                :method="isset($reactionType) ? 'PATCH' : 'POST'"
                action="{{
                    isset($reactionType)
                        ? route('waterhole.cp.reaction-sets.reaction-types.update', compact('reactionSet', 'reactionType'))
                        : route('waterhole.cp.reaction-sets.reaction-types.store', compact('reactionSet'))
                }}"
                enctype="multipart/form-data"
                data-controller="dirty-form"
            />
        </x-waterhole::dialog>
    </turbo-frame>
</x-waterhole::cp>
