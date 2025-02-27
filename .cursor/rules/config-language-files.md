# Use Config and Language Files

Avoid hardcoded strings in your code.

## Example

```php
public function isNormal(): bool
{
    return $article->type === Article::TYPE_NORMAL;
}

return back()->with('message', __('app.article_added'));
```

**Benefits:**

-   Centralized management
-   Localization support
-   Consistent terminology
-   More reliable tests

**Implementation:**

-   Constants: Use model constants or PHP 8.1+ enums
-   Config: Use `config()` helper with Laravel config files
-   Text: Use `__()` or `trans()` with language files
