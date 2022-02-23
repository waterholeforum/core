<x-waterhole::field
    name="title"
    :label="__('waterhole::forum.post-title-label')"
>
    <input
        id="{{ $component->id }}"
        name="title"
        type="text"
        value="{{ old('title', $post->title ?? '') }}"
        class="input"
    >
</x-waterhole::field>

<x-waterhole::field
    name="body"
    :label="__('waterhole::forum.post-body-label')"
>
    <x-waterhole::text-editor
        name="body"
        :id="$component->id"
        :value="old('body', $post->body ?? '')"
        class="input"
    />
</x-waterhole::field>
