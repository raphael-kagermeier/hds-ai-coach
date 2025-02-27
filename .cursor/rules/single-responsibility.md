# Single Responsibility Principle

A class should have only one responsibility.

## Bad Example

```php
public function update(Request $request): string
{
    $validated = $request->validate([
        'title' => 'required|max:255',
        'events' => 'required|array:date,type'
    ]);

    foreach ($request->events as $event) {
        $date = $this->carbon->parse($event['date'])->toString();

        $this->logger->log('Update event ' . $date . ' :: ' . $event['type']);
    }

    $this->event->updateGeneralEvent($request->validated());

    return back();
}
```

## Good Example

```php
public function update(UpdateRequest $request): string
{
    $this->logService->logEvents($request->events);

    $this->event->updateGeneralEvent($request->validated());

    return back();
}
```

This principle states that a class should have only one reason to change. By separating validation, logging, and business logic into different classes, we make our code more maintainable and easier to test.
