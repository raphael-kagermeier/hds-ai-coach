# Use Config and Language Files

Use config and language files, constants instead of text in the code.

## Bad Example

```php
public function isNormal(): bool
{
    return $article->type === 'normal';
}

return back()->with('message', 'Your article has been added!');
```

## Good Example

```php
public function isNormal(): bool
{
    return $article->type === Article::TYPE_NORMAL;
}

return back()->with('message', __('app.article_added'));
```

Using configuration and language files instead of hardcoded strings offers several benefits:

1. **Centralized management**: Change values in one place instead of searching through code
2. **Localization**: Easily translate your application to multiple languages
3. **Consistency**: Ensure the same terminology is used throughout your application
4. **Testability**: More reliable tests that don't break when text changes

For constants, define them in your models or create dedicated enum classes in PHP 8.1+. For configuration values, use Laravel's config files and access them with the `config()` helper. For user-facing text, use language files and the `__()` or `trans()` helpers.
