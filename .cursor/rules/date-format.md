# Store Dates in Standard Format

Store dates in the standard format. Use accessors and mutators to modify date format.

## Bad Example

```php
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->toDateString() }}
{{ Carbon::createFromFormat('Y-d-m H-i', $object->ordered_at)->format('m-d') }}
```

## Good Example

```php
// Model
protected $casts = [
    'ordered_at' => 'datetime',
];

// Blade view
{{ $object->ordered_at->toDateString() }}
{{ $object->ordered_at->format('m-d') }}
```

## Best Practices for Date Handling

1. **Use Carbon**: Laravel integrates Carbon for powerful date manipulation
2. **Consistent Storage**: Always store dates in a standard format in the database (typically Y-m-d H:i:s)
3. **Use Casts**: Define date fields using the `$casts` property in your models
4. **Accessors/Mutators**: Create custom accessors and mutators for specialized formatting needs
5. **Format at Display Time**: Only format dates when displaying them, not when storing them

Example of custom date accessor:

```php
// For specialized formatting needs
public function getFormattedOrderDateAttribute(): string
{
    return $this->ordered_at->format('F j, Y');
}
```

This approach ensures consistency in your database while providing flexibility in how dates are displayed throughout your application.
