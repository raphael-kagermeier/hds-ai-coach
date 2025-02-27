# FilamentPHP Best Practices

Focus on these non-obvious patterns to make your Filament admin panels more maintainable and efficient.

## Use Form Sections for Complex Forms

### Bad Example

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            TextInput::make('title'),
            TextInput::make('slug'),
            RichEditor::make('content'),
            ColorPicker::make('background_color'),
            ColorPicker::make('text_color'),
            FileUpload::make('hero_image'),
            // Many more fields...
        ]);
}
```

### Good Example

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Section::make('Basic Information')
                ->schema([
                    TextInput::make('title'),
                    TextInput::make('slug'),
                    RichEditor::make('content'),
                ]),

            Section::make('Styling')
                ->schema([
                    ColorPicker::make('background_color'),
                    ColorPicker::make('text_color'),
                ]),

            Section::make('Media')
                ->schema([
                    FileUpload::make('hero_image'),
                ]),
        ]);
}
```

Organizing fields into logical sections improves the usability of your forms, especially when they contain many fields. This approach makes forms easier to understand and navigate for administrators.

## Leverage Global Search

Instead of implementing custom search functionality, configure Filament's global search properly:

```php
protected static function getGlobalSearchEloquentQuery(): Builder
{
    return parent::getGlobalSearchEloquentQuery()->with(['author', 'category']);
}

public static function getGloballySearchableAttributes(): array
{
    return ['title', 'content', 'author.name', 'category.name'];
}
```

This provides a powerful search experience while maintaining performance through proper relationship loading.

## Use Custom Pages for Complex Operations

Don't overload resource pages with complex custom actions. For intricate workflows, create dedicated custom pages:

```php
public static function getPages(): array
{
    return [
        'index' => Pages\ListPosts::route('/'),
        'create' => Pages\CreatePost::route('/create'),
        'edit' => Pages\EditPost::route('/{record}/edit'),
        'import' => Pages\ImportPosts::route('/import'), // Custom page
        'analytics' => Pages\PostAnalytics::route('/analytics'), // Custom page
    ];
}
```

This keeps your core resource pages clean while providing dedicated spaces for specialized operations.

## Implement Custom Form Components

For complex, frequently used form patterns, create custom form components instead of repeating field definitions:

```php
// Create a custom component
class AddressFields extends Component
{
    public static function make(): static
    {
        return new static();
    }

    public function getChildComponents(): array
    {
        return [
            TextInput::make('street_address'),
            TextInput::make('city'),
            Select::make('state')
                ->options(States::all()),
            TextInput::make('postal_code'),
        ];
    }
}

// Use it in forms
public static function form(Form $form): Form
{
    return $form
        ->schema([
            // ...
            Section::make('Address')
                ->schema([
                    AddressFields::make(),
                ]),
            // ...
        ]);
}
```

This practice promotes code reuse, consistency, and makes updating shared UI patterns easier.

## Use Filters Instead of Scope Actions

Prefer table filters over scope buttons for frequently used filtering options:

```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            // ...
        ])
        ->filters([
            Filter::make('published')
                ->query(fn (Builder $query) => $query->where('is_published', true)),
            Filter::make('featured')
                ->query(fn (Builder $query) => $query->where('is_featured', true)),
            SelectFilter::make('category')
                ->relationship('category', 'name'),
        ]);
}
```

Filters provide more flexibility than simple scope actions and integrate well with Filament's filtering UI.
