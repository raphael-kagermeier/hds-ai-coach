# Do Not Get Data From the .env File Directly

Pass the data to config files instead and then use the `config()` helper function to use the data in an application.

## Bad Example

```php
$apiKey = env('API_KEY');
```

## Good Example

```php
// config/api.php
'key' => env('API_KEY'),

// Use the data
$apiKey = config('api.key');
```

## Why Avoid Direct .env Access

There are several reasons to avoid accessing `.env` variables directly in your application code:

1. **Caching**: Laravel caches configuration files in production for performance, but direct `env()` calls bypass this cache
2. **Testing**: When running tests, the `.env` file might not be loaded or might contain different values
3. **Clarity**: Config files provide context and organization for environment variables
4. **Default values**: Config files can provide sensible defaults when environment variables are missing
5. **Type casting**: Config files can cast values to appropriate types (booleans, integers, etc.)

The proper approach is to reference environment variables in your configuration files (in the `config` directory) and then use the `config()` helper throughout your application to access these values.
