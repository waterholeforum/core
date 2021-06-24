# Formatting

Waterhole uses the powerful [s9e\TextFormatter](https://github.com/s9e/TextFormatter) library to convert comment formatting syntax such as Markdown into HTML.

Read the [s9e\TextFormatter documentation](https://s9etextformatter.readthedocs.io/Getting_started/How_it_works/) to gain an understanding of how the library works.

## Extending the Formatter

You can extend the TextFormatter instance using the `Formatter` extender:

```php
use Waterhole\Formatter\Formatter;

new Extend\CommentFormatter(function (Formatter $formatter) {
    $formatter->configure(function (Configurator $config) {
        // configure s9e\TextFormatter and its plugins
    });
    
    $formatter->parsing(function (Parser $parser, CommentPost $post) {
        // configure the parser, which converts the post text into XML
    });
    
    $formatter->rendering(function (Renderer $renderer, CommentPost $post, ?User $actor) {
        // configure the renderer, which converts the XML into HTML
    });
})
```

The `CommentFormatter` extender will automatically purge the formatter cache when your extension is enabled/disabled so that your configuration will take effect. During extension development, you will have to manually run `php waterhole cache:clear` to flush the formatter cache.
