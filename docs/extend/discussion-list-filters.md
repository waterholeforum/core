# Discussion List Filters

The discussion list contains an extendable filtering mechanism. There are two types of discussion list filters:

* **Primary filters** are accessed via a top-level route. They usually filter discussions by a certain *attribute*, such as `subscribed`, `muted`, or `trashed`. They usually have a corresponding menu item shown in the index sidebar.

* **Secondary filters** can be applied on top of a primary filter, and use the second route segment (eg. `/subscribed/unread`). They may filter discussions by another attribute and apply a sort order. They usually have a corresponding tab in the discussion list toolbar.

Instead of manually configuring routes and plugging into backend and frontend code, you can use the `DiscussionListFilter` extenders on the backend which will do it all for you. Pass the filter name and an array of JSON API query parameters to apply to the discussion list request when the filter is active:

```php
new Extend\DiscussionListFilterPrimary(
    'my-primary-filter',
    ['filter[myFilter]' => 1]
),
new Extend\DiscussionListFilterSecondary(
    'my-secondary-filter',
    ['sort' => '-myAttribute']
)
```

Note that you will still need to manually add the appropriate menu item on the frontend for your filter.

See the [JSON API]() page for information about adding filters to the JSON API.
