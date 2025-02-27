# Validation

Move validation from controllers to Request classes.

## Bad Example

```php
public function store(Request $request)
{
    $request->validate([
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
        'publish_at' => 'nullable|date',
    ]);

    // Store the post
}
```

## Good Example

```php
public function store(PostRequest $request)
{
    // Store the post
}

class PostRequest extends Request
{
    public function rules(): array
    {
        return [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
            'publish_at' => 'nullable|date',
        ];
    }
}
```

Using Form Request classes for validation keeps your controllers clean and allows you to reuse validation rules across multiple endpoints. It also makes it easier to add custom validation messages and authorization logic.
