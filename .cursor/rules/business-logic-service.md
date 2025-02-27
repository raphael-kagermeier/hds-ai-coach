# Business Logic Should Be in Service Classes

Controllers or Livewire components should only handle HTTP requests/responses. Move business logic to service classes.

## Example

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

Using service classes improves maintainability, testability, and allows for code reuse across your application.
