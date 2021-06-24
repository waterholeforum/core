<div>
  <input id="move_posts" type="radio" name="move_posts" value="1">
  <label for="move_posts">Move posts to:</label>
  <x-waterhole::channel-picker name="channel_id" :exclude="[$channel->id]"/>
</div>

<div>
  <input id="delete_posts" type="radio" name="move_posts" value="0" checked>
  <label for="delete_posts">Delete posts</label>
</div>
