# Do Not Use DocBlocks

DocBlocks reduce readability. Use a descriptive method name and modern PHP features like return type hints instead.

## Bad Example

```php
/**
 * The function checks if given string is a valid ASCII string
 *
 * @param string $string String we get from frontend which might contain
 *                       illegal characters. Returns True is the string
 *                       is valid.
 *
 * @return bool
 * @author  John Smith
 *
 * @license GPL
 */

public function checkString($string)
{
}
```

## Good Example

```php
public function isValidAsciiString(string $string): bool
{
}
```

## Modern PHP Alternatives to DocBlocks

With modern PHP features (especially in PHP 8.0+), many of the reasons for using DocBlocks are no longer necessary:

1. **Type Hints**: Use parameter and return type hints instead of `@param` and `@return`
2. **Named Parameters**: Make parameter purpose clear without documentation
3. **Descriptive Method Names**: Self-documenting code through clear naming
4. **Constructor Property Promotion**: Simplifies property declaration and initialization
5. **Attributes**: Replace annotations with native PHP attributes

DocBlocks still have some valid uses, such as documenting complex algorithms or explaining "why" something is done a certain way, but they should be used sparingly and only when they add real value beyond what the code itself communicates.
