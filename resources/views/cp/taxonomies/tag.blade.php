@php
    $title = isset($tag)
        ? __('waterhole::cp.edit-tag-title')
        : __('waterhole::cp.create-tag-title');
@endphp

<x-waterhole::cp :title="$title">
    <turbo-frame id="modal" data-modal-static>
        <x-waterhole::dialog :title="$title" class="dialog--sm">
            <x-waterhole::form
                :fields="$form->fields()"
                :method="isset($tag) ? 'PATCH' : 'POST'"
                action="{{
                    isset($tag)
                        ? route('waterhole.cp.taxonomies.tags.update', compact('taxonomy', 'tag'))
                        : route('waterhole.cp.taxonomies.tags.store', compact('taxonomy'))
                }}"
                enctype="multipart/form-data"
                data-turbo-frame="tags"
                data-controller="dirty-form"
            />
        </x-waterhole::dialog>
    </turbo-frame>
</x-waterhole::cp>
