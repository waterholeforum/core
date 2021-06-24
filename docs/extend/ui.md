# UI Components

Waterhole provides a number of reusable CSS and Vue components. Generally Vue components are only used for components that have behavior or sufficiently complex markup – otherwise, plain HTML markup should be styled directly with CSS classes.

::: tip Naming Conventions
Waterhole uses the [SUIT CSS naming conventions](https://github.com/suitcss/suit/blob/master/doc/naming-conventions.md) to give CSS class names structure:
 `.ComponentName-descendentName--modifierName`
:::

## Avatars

Use the global `Avatar` Vue component to render a user's avatar.

```html
<Avatar :user="user"/>
```

| Prop   | Type          | Description                                                  |
| :----- | ------------- | ------------------------------------------------------------ |
| `user` | `User | null` | The user to render the avatar for. If `null`, a blank avatar will be rendered. |

## Badges

A Badge is a combination of an icon and a label which represents a toggleable attribute – for example, the "Subscribed", "Muted", "Sticky", "Locked" attributes of a discussion.

To create badge components, Waterhole provides a helper function `createBadge` which accepts an `icon`, a `label`, and any other [VNode data object](https://vuejs.org/v2/guide/render-function.html#The-Data-Object-In-Depth) attributes. For example:

```ts
import { createBadge, Discussion, Extend } from 'waterhole/forum';
import { Component } from 'vue';

new Extend\DiscussionBadges((discussion: Discussion, items: ItemList<Component>) => {
  	if (discussion.myAttribute) {
  			items.add('myAttribute', createBadge({
          	icon: 'fas fa-check', 
          	label: 'My Attribute'
        }));
    }
})
```

## Buttons

Use the `.Button` CSS component to style an element as a pressable button.

Apply the `.Button-icon` class to an icon element ([Font Awesome](https://fontawesome.com/icons?d=gallery&m=free) icons are available). If an icon is present, the label must be wrapped in a `span` element, as spacing will be adjusted if an icon is the only child element.

```html
<!-- Text-only button -->
<a class="Button" href="http://google.com">Google</a>

<!-- Button with icon -->
<button class="Button">
  <i class="Button-icon fas fa-check"></i>
  <span>Save Changes</span>
</button>

<!-- Icon-only button -->
<button class="Button">
  <i class="Button-icon fas fa-ellipsis-h"></i>
</button>
```

The following modifiers are available:

| Modifier               | Description                                               |
| :--------------------- | --------------------------------------------------------- |
| `.Button--primary`     | Primary action button                                     |
| `.Button--transparent` | Blends in with the background                             |
| `.Button--block`       | Takes up 100% width of its container                      |
| `.Button--small`       | Small button size                                         |
| `.Button--tooltip`     | Button in the style of a tooltip                          |
| `.Button--link`        | Button in the style of a link                             |
| `.Button--fab`         | Becomes a floating action button at the mobile breakpoint |

Arrange multiple buttons in a group by wrapping them in `.ButtonGroup`.

## Content Placeholders

Content placeholders inform the user that some content is unavailable. This includes scenarios where there was an error loading the content, or where no content has been created yet.

Apply `.ContentPlaceholder` to the container, `.ContentPlaceholder-visual` to the icon element, and `.ContentPlaceholder-heading` to the heading element. The container should only have direct element children (all text should be wrapped in an enclosing element) for proper spacing between each child.

```html
<div class="ContentPlaceholder">
  <i class="ContentPlaceholder-visual fas fa-search"></i>
  <h4 class="ContentPlaceholder-heading">No content was found.</h4>
  <p>Why don't you try looking somewhere else?</p>
  <button class="Button">Go Back</button>
</div>
```

## Document Title & Attributes

You can declaratively set the document `<title>` on a page by including the global `DocumentTitle` component. The forum name will automatically be appended.

```html
<DocumentTitle title="Hello World"/>
```

To declaratively set attributes on the `<html>` element (useful for [styling hooks]()) use the `GlobalAttrs` component:

```html
<GlobalAttrs :data-my-var="myVar"/>
```

The [`GlobalEvents` component]() is also available.

## Emojify

Use the global `Emojify` Vue component to parse emoji into [Twemoji](). The component must have exactly one element child (text cannot be the direct descendant).

```html
<Emojify>
  <span>How good is Twemoji 😍</span>
</Emojify>
```

## Modals & Dialogs

Display any component as a [modal](https://en.wikipedia.org/wiki/Modal_window) using the `app.modal` method, which accepts a [render function](https://vuejs.org/v2/guide/render-function.html):

```ts
import { app } from 'waterhole/forum';

app.modal(
    h => h(MyComponent, { props: { foo: 'bar' }})
);
```

This will mount a [vue-simple-components `Modal`]() onto the page with your component in its default slot, and destroy it when the modal is closed. You can pass VNodeData for the `Modal` component as the second argument:

```ts
app.modal(
    h => h(MyDialogComponent, { props: { foo: 'bar' }}),
    { props: { undismissable: true }}
);
```

Emit the `close` event from your dialog component to close the modal.

The `.Dialog` CSS component can be used to style a dialog:

```html
<div class="Dialog">
  <button
    class="Dialog-close Button Button--transparent"
    @click="$emit('close')"
  >
    <i class="Button-icon fas fa-times"></i>
  </button>
  
  <div class="Dialog-header">
    <h2>Hello, world!</h2>
  </div>
  
  <div class="Dialog-body">
    <p>How do you do?</p>
  </div>
</div>
```

## Dropdowns

The [vue-simple-components `Dropdown`]() component is exposed as a global `Dropdown` component. Example usage with a dropdown menu:

```html
<Dropdown>
  <button class="Button">Toggle</button>
  
  <template #content>
  	<div class="Menu">
    	<div class="MenuItem">Hello, world!</div>
    </div>
  </template>
</Dropdown>
```

## Menus, Navs, & Tabs

`.MenuItem`, `.MenuDivider`, and `.MenuHeading` are classes for styling the contents of a menu. Their appearance depends on their container. A generic "menu" can be constructed with these classes, and then it can be rendered in multiple contexts (for example, both as a dropdown menu and as a set of tabs).

`.Menu` is a container which displays the items vertically in the style of a [dropdown]() menu.

`.Nav` is a container which displays the items vertically in the style of a navigation list (eg. in the sidebar of the index page).

`.Tabs` is a container which displays the items horizontally in the style of tabs. The [vue-simple-components `Tabs`]() component is exposed globally as `Tabs` and can be used if an "active indicator" is desired (a colored bar which is automatically positioned beneath the active item).

```html
<div class="Menu">
  <router-link :to="/foo" class="MenuItem">Foo</a>
  <router-link :to="/bar" class="MenuItem">Bar</a>
</div>

<div class="Nav">
  <router-link :to="/foo" class="MenuItem">Foo</a>
  <router-link :to="/bar" class="MenuItem">Bar</a>
</div>

<Tabs>
  <router-link :to="/foo" class="MenuItem">Foo</a>
  <router-link :to="/bar" class="MenuItem">Bar</a>
</Tabs>
```

Because dynamically constructing menus is so common, Waterhole provides helper functions to avoid having to create a new component or render function for every item. `createMenuItem` accepts an `icon`, a `label`, and any other [VNode data object](https://vuejs.org/v2/guide/render-function.html#The-Data-Object-In-Depth) attributes. `createMenuHeading` just accepts the heading text.

```ts
import { Component } from 'vue';
import { ItemList, createMenuItem, createMenuHeading } from 'waterhole/forum';

const items = new ItemList<Component>();

items.add('fooHeading', createMenuHeading('Hello'));
items.add('foo', createMenuItem({
    icon: 'fas fa-check',
    label: 'Do Something',
    on: { click: () => {} }
}));

const components = items.toArray();
```

To create menus with dividers, `ItemList`s which contain groups of menu item components should be nested within a parent `ItemList`. The `flattenMenuItems` helper function can then be used to flatten the groups into a flat array of components, and it will automatically add divider components in-between each group.

```ts
import { Component } from 'vue';
import { ItemList, flattenMenuItems } from 'waterhole/forum';

const group1 = new ItemList<Component>();
// add items to group 1...

const group2 = new ItemList<Component>();
// add items to group 2...

const items = new ItemList<ItemList<Component>>();
items.add('group1', group1);
items.add('group2', group2);

const components = flattenMenuItems(items);
// [...group1 items, divider, ...group2 items]
```


## Forms

Use the `.Field` CSS component to display a series of form fields with appropriate spacing. Apply `.Field-label` to the `label` element, `.Field-status` to any help or error text below the input. `.Field--error` and `.Field--success` modifiers can be used to change the appearance of the field.

Use the `.Input` class to style a form control like an `input`, `select`, or  `textarea`.

Use the `.Checkbox` or `.Radio` class on a `label` element wrapping an `input` of the same type.

```html
<div class="Field">
  <label class="Field-label">Name</label>
  <input class="Input" type="text">
  <div class="Field-status">Enter your full name.</div>
</div>

<div class="Field Field--error">
  <label class="Checkbox">
    <input type="checkbox">
    Agree to the terms
  </label>
  <div class="Field-status">You must agree to the terms!</div>
</div>
```

## Tooltips

The [vue-simple-components `Tooltip`]() component is exposed globally as `Tooltip` :

```html
<Tooltip title="But read this first!">
  <button class="Button">Click Me</button>
</Tooltip>

<Tooltip>
  <button class="Button">Click Me</button>
  <template #title>
  	But read <small>this</small> first!
  </template>
</Tooltip>
```

## Toaster

An instance of the [vue-simple-components `Toaster`]() component is available as the `app.toaster` variable. This can be used to show pop-up messages that disappear after a few seconds.

```ts
import { app } from 'waterhole/forum';

app.toaster.show({
    icon: 'fas fa-bread-slice',
    message: 'The best thing since sliced bread!',
    action: {
        label: 'Buy Toast',
        click: () => window.location = 'http://toast.com'
    }
});
```

| Prop      | Type                                 | Description                                  |
| :-------- | ------------------------------------ | -------------------------------------------- |
| `message` | `string`                             | The message to display                       |
| `type`    | `null | 'warning' | 'danger'`        | The type of message, used to style the toast |
| `icon`    | `string`                             | FontAwesome icon class                       |
| `action`  | `{ label: string, click: Function }` | Show an action button                        |

## Other Components

Some other [vue-simple-components]() are exposed globally. See their documentation for usage details:

* `Swipable`
* `Overlay`
* `Spinner`
* `SuggestionMenu`
* `GrowingInput`

