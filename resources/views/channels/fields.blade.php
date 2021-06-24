<div>
  <label for="name">Name</label>
  <input id="name" name="name" type="text" value="{{ old('name', $channel->name ?? '') }}">
  @error('name') <div>{{ $message }}</div> @enderror
</div>

<div>
  <label for="slug">Slug</label>
  <input id="slug" name="slug" type="text" value="{{ old('slug', $channel->slug ?? '') }}">
  @error('slug') <div>{{ $message }}</div> @enderror
</div>

<div>
  <label for="emoji">Emoji</label>
  <input id="emoji" name="emoji" type="text" value="{{ old('emoji', $channel->icon ?? '') }}">
  @error('emoji') <div>{{ $message }}</div> @enderror
</div>

<div>
  <label for="description">Description</label>
  <textarea id="description" name="description">{{ old('description', $channel->description ?? '') }}</textarea>
  @error('description') <div>{{ $message }}</div> @enderror
</div>
