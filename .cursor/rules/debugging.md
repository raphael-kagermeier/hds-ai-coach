# Effective Debugging in Laravel Applications

This guide covers effective debugging techniques for Laravel applications.

## 1. Laravel Debugbar for Development

Use Laravel Debugbar to identify and resolve performance issues, track queries, and monitor application behavior.

### Bad Debugging Practices

```php
// Avoid these practices
dd($data);
dump($query->toSql());
Log::info('Debug message');

// Manual timing is cumbersome
$start = microtime(true);
// ... code ...
$end = microtime(true);
echo "Time: " . ($end - $start);
```

This approach interrupts application flow, clutters the UI, and may accidentally reach production.

### Effective Use of Laravel Debugbar

```php
// Install: composer require barryvdh/laravel-debugbar --dev
// Configure in .env: APP_DEBUG=true, DEBUGBAR_ENABLED=true

// Better debugging with Debugbar
Debugbar::info($data);
Debugbar::warning('Warning message');
Debugbar::startMeasure('process-name', 'Description');
// ... code ...
Debugbar::stopMeasure('process-name');

// Or use the helper
debugbar()->addMessage('Debug message', 'category');
```

Laravel Debugbar automatically collects:

-   Database queries with execution time
-   Request/response data
-   Route information
-   Log messages and more

The Debugbar appears as a toolbar at the bottom of your browser during development and is automatically disabled in production.

## 2. Accessing Debug Information Programmatically

You can also access debug information programmatically using MCP tools.

### Retrieving Request Logs

```
// Get recent requests
get_request_logs()

// Filter by method
get_request_logs(method: "POST")

// Filter by URI pattern
get_request_logs(uri: "/api/")

// Pagination
get_request_logs(limit: 10, offset: 20)
```

### Accessing Request Details

```
// Get all details for a request
get_request_details(id: "request_id")

// Get specific properties
get_request_details(
  id: "request_id",
  properties: ["queries", "time"]
)
```

### Quick Access to Specific Debug Data

```
// Common debug data functions
get_queries_data(id: "request_id")    // Database queries
get_time_data(id: "request_id")       // Timing information
get_exceptions_data(id: "request_id") // Exceptions
get_views_data(id: "request_id")      // View rendering
get_session_data(id: "request_id")    // Session data
get_route_data(id: "request_id")      // Route information
```

These tools provide programmatic access to debug data, allowing for automated analysis and custom debugging workflows.
