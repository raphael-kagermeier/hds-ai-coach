# Business Logic Should Be in Service Class

A controller must have only one responsibility, so move business logic from controllers to service classes.

## Bad Example

```php
public function store(Request $request)
{
    if ($request->hasFile('image')) {
        $request->file('image')->move(public_path('images') . 'temp');
    }

    // More business logic here
}
```

## Good Example

```php
public function store(Request $request)
{
    $this->articleService->handleUploadedImage($request->file('image'));

    // Controller focuses on HTTP concerns
}

class ArticleService
{
    public function handleUploadedImage($image): void
    {
        if (!is_null($image)) {
            $image->move(public_path('images') . 'temp');
        }
    }
}
```

Using service classes to encapsulate business logic makes your application more maintainable and testable. It allows controllers to focus on their primary responsibility: handling HTTP requests and responses. Service classes can be reused across different parts of your application.
