# Prefer Descriptive Method and Variable Names Over Comments

Use clear, descriptive names for methods and variables instead of relying on comments to explain what your code does.

## Bad Example

```php
// Determine if there are any joins
if (count((array) $builder->getQuery()->joins) > 0)
```

## Good Example

```php
if ($this->hasJoins())
```

Descriptive method and variable names make your code self-documenting and easier to understand. Good naming reduces the need for comments and makes your codebase more maintainable. When naming methods:

-   Use verbs for actions (e.g., `calculateTotal()`, `sendNotification()`)
-   Use `is`, `has`, `should` prefixes for boolean methods (e.g., `isValid()`, `hasPermission()`)
-   Use nouns for accessors (e.g., `getFullName()`, `totalPrice()`)

Remember that code is read much more often than it is written, so prioritize readability through good naming.
