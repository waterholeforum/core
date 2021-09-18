<div class="field">
    <label for="title" class="field__label">Title</label>
    <p class="field__description">
        Be specific and imagine you’re asking a question to another person
    </p>
    <input
        id="title"
        name="title"
        type="text"
        value="{{ old('title', $post->title ?? '') }}"
        class="input full-width"
    >
    @error('title') <div class="field__status color-danger">{{ $message }}</div> @enderror
</div>

<div class="field">
    <label for="body" class="field__label">Body</label>
    <p class="field__description">
        Include all the information someone would need to answer your question
    </p>
    <div class="input composer">
        <textarea name="body" id="body">{{ old('body', $post->body ?? '') }}</textarea>
    </div>
{{--    <textarea--}}
{{--        id="body"--}}
{{--        name="body"--}}
{{--        class="input"--}}
{{--    >{{ old('body', $post->body ?? '') }}</textarea>--}}
    @error('body') <div>{{ $message }}</div> @enderror
</div>
