# Prefer Eloquent Over Query Builder and Raw SQL

Prefer to use Eloquent over using Query Builder and raw SQL queries. Prefer collections over arrays.

## Bad Example

```sql
SELECT *
FROM `articles`
WHERE EXISTS (SELECT *
              FROM `users`
              WHERE `articles`.`user_id` = `users`.`id`
              AND EXISTS (SELECT *
                          FROM `profiles`
                          WHERE `profiles`.`user_id` = `users`.`id`)
              AND `users`.`deleted_at` IS NULL)
AND `verified` = '1'
AND `active` = '1'
ORDER BY `created_at` DESC
```

## Good Example

```php
Article::has('user.profile')->verified()->latest()->get();
```

Eloquent allows you to write readable and maintainable code. It provides built-in tools like soft deletes, events, scopes, and relationships that make your code more expressive and powerful. Collections offer numerous helpful methods that make working with data more convenient than using plain arrays.
