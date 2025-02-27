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
