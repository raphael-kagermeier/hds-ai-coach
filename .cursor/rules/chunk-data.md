# Chunk Data for Data-Heavy Tasks

Process large datasets in batches to prevent memory issues.

## Bad Example

```php
$users = $this->get();

foreach ($users as $user) {
    // Process each user
}
```

## Good Example

```php
$this->chunk(500, function ($users) {
    foreach ($users as $user) {
        // Process each user
    }
});
```

Use chunking for background jobs, exports, and data-intensive operations. Laravel provides `chunk()` and `chunkById()` methods for efficient processing.
