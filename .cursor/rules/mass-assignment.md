# Mass Assignment

Use Eloquent's mass assignment features to simplify your code and improve security.

## Bad Example

```php
$article = new Article;
$article->title = $request->title;
$article->content = $request->content;
$article->verified = $request->verified;

// Add category to article
$article->category_id = $category->id;
$article->save();
```

## Good Example

```php
$category->article()->create($request->validated());
```

Mass assignment in Laravel allows you to assign multiple model attributes at once. When combined with form requests and the `$fillable` or `$guarded` properties on your models, it provides a secure and concise way to create or update records. Always use `validated()` from form requests to ensure only validated data is used for mass assignment.
