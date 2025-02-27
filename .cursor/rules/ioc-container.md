# Use IoC Container or Facades Instead of New Class

new Class syntax creates tight coupling between classes and complicates testing. Use IoC container or facades instead.

## Bad Example

```php
$user = new User;
$user->create($request->validated());
```

## Good Example

```php
public function __construct(protected User $user) {}

...

$this->user->create($request->validated());
```

## Benefits of Dependency Injection and IoC Container

Laravel's IoC (Inversion of Control) container is a powerful tool for managing class dependencies and performing dependency injection. Using the container instead of directly instantiating objects with `new` offers several advantages:

1. **Testability**: Dependencies can be easily mocked in tests
2. **Flexibility**: Implementation details can be changed without modifying dependent code
3. **Decoupling**: Classes depend on abstractions rather than concrete implementations
4. **Single Responsibility**: Each class focuses on its core functionality

You can use dependency injection in controllers, middleware, event listeners, and other parts of your Laravel application. For simpler cases, you can also use Laravel's facades, which provide a static interface to classes that are available in the service container.
