<turbo-frame id="widget_{{ $id }}">
    @components([$widget['component']], compact('id') + $widget)
</turbo-frame>
