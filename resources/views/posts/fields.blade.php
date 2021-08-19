<div>
  <label for="title">Title</label>
  <input id="title" name="title" type="text" value="{{ old('title', $post->title ?? '') }}">
  @error('title') <div>{{ $message }}</div>@enderror
</div>

<div>
  <label for="body">Body</label>
  <textarea id="body" name="body">{{ old('body', $post->body ?? '') }}</textarea>
  @error('body') <div>{{ $message }}</div>@enderror
</div>
