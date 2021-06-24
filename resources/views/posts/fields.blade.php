<div>
  <label for="channel">Channel</label>
  <x-waterhole::channel-picker id="channel" name="channel_id" :value="old('channel_id', $post->channel_id ?? request('channel'))"/>
</div>

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
