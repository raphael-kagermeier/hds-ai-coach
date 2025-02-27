# Avoid N+1 Problem

Do not execute queries in Blade templates and use eager loading (N + 1 problem).

## Bad Example

```blade
@foreach (User::all() as $user)
    {{ $user->profile->name }}
@endforeach
```

In this example, for 100 users, 101 DB queries will be executed:

-   1 query to fetch all users
-   100 additional queries (one per user) to fetch each user's profile

## Good Example

```php
$users = User::with('profile')->get();

@foreach ($users as $user)
    {{ $user->profile->name }}
@endforeach
```

In this example, for 100 users, only 2 DB queries will be executed:

-   1 query to fetch all users
-   1 query to fetch all profiles for those users

The N+1 query problem is a common performance issue in applications using ORMs. It occurs when your code executes N additional queries to fetch related data for N parent records. Using eager loading with the `with()` method solves this by loading all related data in a single query.
