<div {{ $attributes->merge([
    'data-controller' => $attributes->prepends('sortable'),
    'data-sortable-instructions-value' => __('waterhole::system.sortable-instructions'),
    'data-sortable-drag-start-announcement-value' => __('waterhole::system.sortable-drag-start-announcement'),
    'data-sortable-drag-over-announcement-value' => __('waterhole::system.sortable-drag-over-announcement'),
    'data-sortable-drop-announcement-value' => __('waterhole::system.sortable-drop-announcement'),
    'data-sortable-drag-cancel-announcement-value' => __('waterhole::system.sortable-drag-cancel-announcement'),
]) }}>
    {{ $slot }}
</div>
