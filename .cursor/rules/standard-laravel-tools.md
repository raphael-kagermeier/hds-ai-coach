# Use Standard Laravel Tools

Prefer to use built-in Laravel functionality and community packages instead of using 3rd party packages and tools.

## Recommended Tools by Task

| Task                      | Standard tools                         | 3rd party tools to avoid                                |
| ------------------------- | -------------------------------------- | ------------------------------------------------------- |
| Authorization             | Policies                               | Entrust, Sentinel and other packages                    |
| Compiling assets          | Laravel Mix, Vite                      | Grunt, Gulp, 3rd party packages                         |
| Development Environment   | Laravel Sail, Homestead                | Custom Docker setups                                    |
| Deployment                | Laravel Forge                          | Deployer and other solutions                            |
| Unit testing              | PHPUnit, Mockery                       | Phpspec                                                 |
| Browser testing           | Laravel Dusk                           | Codeception                                             |
| DB                        | Eloquent                               | SQL, Doctrine                                           |
| Templates                 | Blade                                  | Twig                                                    |
| Working with data         | Laravel collections                    | Arrays                                                  |
| Form validation           | Request classes                        | 3rd party packages, validation in controller            |
| Authentication            | Built-in                               | 3rd party packages, your own solution                   |
| API authentication        | Laravel Passport, Laravel Sanctum      | 3rd party JWT and OAuth packages                        |
| Creating API              | Built-in                               | Dingo API and similar packages                          |
| Working with DB structure | Migrations                             | Working with DB structure directly                      |
| Localization              | Built-in                               | 3rd party packages                                      |
| Realtime user interfaces  | Laravel Echo, Pusher                   | 3rd party packages and working with WebSockets directly |
| Generating testing data   | Seeder classes, Model Factories, Faker | Creating testing data manually                          |
| Task scheduling           | Laravel Task Scheduler                 | Scripts and 3rd party packages                          |
| DB                        | MySQL, PostgreSQL, SQLite, SQL Server  | MongoDB                                                 |

Using Laravel's built-in tools and officially supported packages ensures better compatibility, community support, and long-term maintainability. The Laravel ecosystem is designed to work together seamlessly, and deviating from it often introduces unnecessary complexity and potential issues.
