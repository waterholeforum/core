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

<!-- TODO: icon, cover -->
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

<hr>

<div>
  <label for="instructions">Posting Instructions</label>
  <textarea id="instructions" name="instructions">{{ old('instructions', $channel->instructions ?? '') }}</textarea>
  @error('instructions') <div>{{ $message }}</div> @enderror
</div>

<div>
  <label for="hide_sidebar">Hide Sidebar</label>
  <input type="hidden" name="hide_sidebar" value="0">
  <input type="checkbox" id="hide_sidebar" name="hide_sidebar" value="1" @if (old('hide_sidebar', $channel->hide_sidebar ?? false)) checked @endif>
  @error('hide_sidebar') <div>{{ $message }}</div> @enderror
</div>

<div>
  <label for="sandbox">Sandbox</label>
  <input type="hidden" name="sandbox" value="0">
  <input type="checkbox" id="sandbox" name="sandbox" value="1" @if (old('sandbox', $channel->sandbox ?? false)) checked @endif>
  @error('sandbox') <div>{{ $message }}</div> @enderror
</div>

<fieldset>
  <legend>Sort Options</legend>
  <input type="checkbox" id="custom_sorts" name="custom_sorts" value="1" @if (old('custom_sorts', $channel?->default_sort || $channel?->sorts)) checked @endif>
  <label for="custom_sorts">Customize sort options</label>

  @foreach (\Waterhole\Extend\FeedSort::getComponents()->map(fn($class) => app($class)) as $sort)
    @php $handle = $sort->handle(); @endphp
    <div>
      <input type="radio" id="default_sort" name="default_sort" value="{{ $sort->handle() }}" @if (old('default_sort', $channel->default_sort ?? config('waterhole.forum.default_sort')) === $sort->handle()) checked @endif>
      <input type="checkbox" id="sort_{{ $handle }}" name="sorts[]" value="{{ $sort->handle() }}" @if (in_array($handle, old('sorts', $channel->sorts ?? config('waterhole.forum.sorts', [])))) checked @endif>
      <label for="sort_{{ $handle }}">{{ $sort->name() }}</label>
    </div>
  @endforeach
</fieldset>
