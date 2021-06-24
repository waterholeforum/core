<x-waterhole::action-buttons
    :for="$for"
    :only="$only"
    :exclude="$exclude"
    :placement="$placement"
    :button-attributes="['class' => 'btn btn--icon btn--transparent btn--sm']"
    tooltips
    :limit="1"
>
    @isset($button)
        <x-slot:button>{{ $button }}</x-slot:button>
    @endif
</x-waterhole::action-buttons>
