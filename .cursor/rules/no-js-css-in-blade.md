# No JS and CSS in Blade Templates

Do not put JS and CSS in Blade templates and do not put any HTML in PHP classes.

## Bad Example

```javascript
let article = `{{ json_encode($article) }}`;
```

## Better Example

```php
<input id="article" type="hidden" value='@json($article)'>

// Or

<button class="js-fav-article" data-article='@json($article)'>{{ $article->name }}<button>
```

In a Javascript file:

```javascript
let article = $("#article").val();
```

## Best Practice

The best approach is to use specialized PHP to JS data transfer packages or techniques:

1. Use Laravel's Vite with proper asset organization
2. Consider using Livewire or Inertia.js for more complex interactions
3. Use data attributes for simple data passing
4. For complex data structures, use a dedicated endpoint that returns JSON

Separating concerns between markup (Blade), styling (CSS), and behavior (JavaScript) leads to more maintainable code. It also improves caching, allows for better testing, and makes your application easier to debug.
