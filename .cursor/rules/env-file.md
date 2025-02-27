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

The proper approach is to reference environment variables in your configuration files (in the `config` directory) and then use the `config()` helper throughout your application to access these values.
