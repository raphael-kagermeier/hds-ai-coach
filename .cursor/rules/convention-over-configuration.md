# Convention Over Configuration

As long as you follow certain conventions, you do not need to add additional configuration.

## Bad Example

```php
// Table name 'Customer'
// Primary key 'customer_id'
class Customer extends Model
{
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    protected $table = 'Customer';
    protected $primaryKey = 'customer_id';

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_customer', 'customer_id', 'role_id');
    }
}
```

## Good Example

```php
// Table name 'customers'
// Primary key 'id'
class Customer extends Model
{
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }
}
```

Laravel follows the "convention over configuration" principle, which means it makes assumptions about how you want to configure your application based on common best practices. By following Laravel's conventions, you can significantly reduce the amount of configuration code needed:

1. **Model and table names**: Models are singular, tables are plural
2. **Primary keys**: Named 'id' by default
3. **Timestamps**: Created_at and updated_at by default
4. **Relationships**: Follow naming patterns for tables and foreign keys
5. **Route parameters**: Match model names

This approach leads to cleaner, more maintainable code with less boilerplate.
