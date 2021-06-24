# Post Types

In Waterhole, there are various different types of posts that can appear in a discussion. The most common type is a "comment", which is what you would expect – a block of user-generated text. But there are also "event" posts which describe actions that have been performed on the discussion – for example, moderator actions like moving the discussion to a different category or changing its title.

Post types are implemented using single-table inheritance via the [Parental](https://github.com/calebporzio/parental) package.

### Defining a New Post Type

To define a new post type, create a class which extends `Waterhole\Post` and implements the `Parental\HasParent` trait:

```php
namespace Acme\HelloWorld;

use Waterhole\Models\Post;
use Parental\HasParent;

class FooPost extends Post
{
  	use HasParent;
}
```

Then register it with the backend `PostType` extender:

```php
new Extend\PostType(Acme\HelloWorld\FooPost::class)
```

### Rendering Post Types

You must create a Vue component to render your post type on the frontend, or it will not be displayed. A generic `EventPost` component is available which you can compose to render your post type in the same style as other event posts. The default slot is displayed following an icon and the user's name.

```vue
<template>
  <PostEvent
    :post="post"
    post-class="FooPost"
    icon-class="fas fa-pencil-alt"
  >
    did something
  </PostEvent>
</template>

<script lang="ts">
import Vue, { PropType } from 'vue';
import { Post, EventPost } from 'waterhole/forum';

export default Vue.extend({
    name: 'FooPost',
    components: { PostEvent },
    props: {
        post: Object as PropType<Post>
    }
});
</script>
```

Once you have created your component, register it with the frontend `PostType` extender to tell Waterhole to use it to render any posts of your post type:

```ts
import FooPost from './components/FooPost.vue';

new Extend.PostType('Acme\\HelloWorld\\FooPost', FooPost)
```

### Creating New Posts

To add a new instance of your post type to the end of a discussion, use the `appendPost` method on the discussion:

```php
$post = new FooPost;
$post->user_id = 1;

$discussion = Discussion::find(1);
$discussion->appendPost($post);
```

This will set up the relationship between the post and the discussion, set the post "number" (a unique index within the discussion), save the post, and update discussion metadata if applicable.

### Storing Content

Post types can make use of the `content` column to store information for each instance. For example, the "discussion was renamed" post type stores the old and new discussion title, and the "discussion was moved" post type stores the IDs of the old and new category.

To achieve this, most often you will want to cast the `content` column to be stored as `json` on your post type's model. It is also good practice to standardize the format of your content JSON by implementing a static constructor:

```php
class FooPost extends Post
{
  	use HasParent;
    
    protected $casts = [
        'content' => 'json'
    ];
    
    public static function make(string $foo, int $bar)
    {
        return new static([
            'content' => ['foo' => $foo, 'bar' => $bar]
        ]);
    }
}

// later
$post = FooPost::make('hello', 123);
$post->user_id = 1;
```

### Reversible Posts

Sometimes it may be desirable for a new post to be merged with the post before it, or to cause it to be removed. Consider the following scenario looking at the case of the "discussion was renamed" post type:

* A discussion is titled "Old title".
* A moderator renames it to "New title", and a new "discussion renamed" post is created with the content `["Old title", "New title"]`.
* The moderator then immediately renames it to "Better title". Instead of cluttering up the discussion by creating another new "discussion renamed" post, it would be cleaner to change the content of the previous one to `["Old title", "Better title"]`.
* The moderator then immediately rename it back to "Old title". Instead of changing the content of the previous post to `["Old title", "Old title"]`, it would be better to delete the previous post altogether.

This can be achieved by implementing the `Waterhole\Posts\HasReversibleContent` trait on your model. This trait automatically casts your `content` to JSON and requires it to be formatted as a two-element array containing the old and new content (`[old, new]`).

```php
class RenamedPost extends Post
{
  	use HasParent, HasReversibleContent;
    
    public static function make(?string $old, string $new)
    {
        return new static([
            'content' => [$old, $new]
        ]);
    }
}
```

### Inverse Posts

It may also be desirable to define a post type as the "inverse" of another post type, so that if a post is made after its inverse, they will cancel each other out and the previous post will be destroyed. An example of this is the "discussion was trashed" and "discussion was restored" post types.

This can be achieved by implementing the `Waterhole\Posts\HasInverse` trait on your model, and setting the inverse post type:

```php
class DeletedPost extends Post
{
    use HasParent, HasInverse;

    protected $inverse = RestoredPost::class;
}
```

