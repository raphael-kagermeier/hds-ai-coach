# Other Good Practices

Additional best practices for Laravel development.

## General Guidelines

1. **Avoid alien patterns**: Don't use patterns and tools that are alien to Laravel and similar frameworks. If you prefer a different framework's approach, consider using that framework instead.

2. **Keep routes clean**: Never put any logic in routes files. Routes should only define endpoints and their corresponding controller methods.

3. **Minimize PHP in Blade**: Keep Blade templates focused on presentation. Complex logic should be handled in controllers, services, or view composers.

4. **In-memory testing**: Use in-memory SQLite database for testing to improve test speed and isolation.

5. **Respect framework features**: Don't override standard framework features to avoid problems related to updating the framework version.

6. **Use modern PHP**: Leverage modern PHP syntax where possible, but don't sacrifice readability.

7. **Be cautious with View Composers**: Avoid using View Composers and similar tools unless you really understand their implications. In most cases, there's a better way to solve the problem.

8. **Early returns**: Use early returns to reduce nesting and improve readability.

9. **Cruddy by design**: Follow the "cruddy by design" pattern for organizing controller methods.

10. **Short, declarative functions**: Keep functions short and focused on a single responsibility.

11. **Follow security best practices**: Use Laravel's built-in security features like CSRF protection, input validation, and authentication.

By following these practices, you'll create Laravel applications that are maintainable, performant, and aligned with the framework's philosophy.
