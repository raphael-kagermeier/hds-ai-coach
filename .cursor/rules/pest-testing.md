# Pest Testing in PHP

Write expressive, maintainable tests using Pest, a modern testing framework for PHP.

## Use Descriptive Test Names

### Example

```php
test('authenticated users can view the dashboard', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/dashboard');

    $response->assertStatus(200);
});
```

Using descriptive, sentence-like test names makes your test suite act as documentation. When tests fail, descriptive names immediately communicate what functionality is broken without having to read the test implementation.

## Leverage Higher-Order Testing

```php
it('can be created')
    ->expect(fn () => User::create([
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => bcrypt('password'),
    ]))
    ->toBeInstanceOf(User::class);

it('can be updated', function () {
    $user = User::factory()->create(['name' => 'John Doe']);

    $user->update(['name' => 'Jane Doe']);

    expect($user->fresh()->name)->toBe('Jane Doe');
});

it('can be deleted', function () {
    $user = User::factory()->create();

    $user->delete();

    expect($user)->not->toExist();
});
```

Pest's higher-order testing provides a more fluent API for writing tests. Using `it()` and `expect()` makes tests more readable and reduces boilerplate. The test name completes the sentence started with "it", creating a more natural language description of what's being tested.

## Use Datasets for Multiple Test Cases

```php
it('validates input', function (array $data, array $errors) {
    $response = $this->post('/users', $data);

    $response->assertSessionHasErrors($errors);
})->with([
    'required fields' => [
        [], ['name', 'email', 'password']
    ],
    'email format' => [
        ['name' => 'John Doe', 'email' => 'invalid-email', 'password' => 'password'],
        ['email']
    ],
    'password length' => [
        ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'pass'],
        ['password']
    ],
]);
```

Datasets allow you to test multiple scenarios using a single test definition, reducing duplication and making your test suite more maintainable. Each dataset entry can be named (as shown above) or provided as a simple array of arguments.

## Organize Tests with Proper Grouping

```php
describe('Authentication', function () {
    test('user can register', function () {
        // Registration test
    });

    test('user can login', function () {
        // Login test
    });

    test('user can reset password', function () {
        // Password reset test
    });
});

describe('User Profile', function () {
    test('profile can be updated', function () {
        // Profile update test
    });
});
```

Using `describe()` blocks helps organize tests into logical groups. This improves readability and helps maintain a clear structure as your test suite grows. It also makes the test output more organized when running tests.

## Leverage Test Isolation with Setup and Teardown

### Example

```php
describe('Posts', function () {
    beforeEach(function () {
        // Scoped setup only for this describe block
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->post = Post::factory()->create(['user_id' => $this->user->id]);
    });

    test('user can view their posts', function () {
        $response = $this->get('/posts');

        $response->assertSee($this->post->title);
    });
});

describe('Guest Access', function () {
    test('guest cannot create posts', function () {
        // No conflicting setup here
        $response = $this->post('/posts', ['title' => 'New Post']);

        $response->assertRedirect('/login');
    });
});
```

Properly scoped `beforeEach()` and `afterEach()` hooks ensure test isolation and prevent unintended side effects between tests. Organize your hooks within describe blocks to limit their scope to only the tests that need them.

## Embrace Pest's Expectations API

### Example

```php
test('post has correct attributes', function () {
    $post = Post::factory()->create([
        'title' => 'My First Post',
        'published' => true
    ]);

    expect($post)
        ->title->toBe('My First Post')
        ->published->toBeTrue()
        ->created_at->not->toBeNull()
        ->created_at->toBeInstanceOf(Carbon::class)
        ->created_at->toBeInstanceOf(Carbon::class, fn ($date) => $date->isToday());
});
```

Pest's expectations API offers a fluent, chainable syntax that makes assertions more readable. You can chain multiple expectations, access properties directly, and use arrow functions for custom assertions, resulting in more concise and expressive tests.

## Follow Test-Driven Development

1. Write a failing test for new functionality
2. Write the minimal code to make the test pass
3. Refactor your code while keeping tests green

This approach ensures your code is testable by design and that tests genuinely verify your application's behavior rather than just confirming what was already implemented.

Remember that tests are not just for verification but also serve as living documentation for your codebase. Well-written Pest tests can help new developers understand your application's behavior and constraints.

## Testing FilamentPHP Applications

Effectively test your FilamentPHP admin panels with these structured approaches.

### Configure Test Authentication

Always set up authentication in your TestCase to access FilamentPHP admin panels:

```php
protected function setUp(): void
{
    parent::setUp();

    $this->actingAs(User::factory()->create());
}
```

For multiple panels, specify the current panel being tested:

```php
use Filament\Facades\Filament;

// In your test or setUp method
Filament::setCurrentPanel(
    Filament::getPanel('app'), // Where 'app' is the panel ID
);
```

### Test Resource Pages

#### Testing List Pages

##### Rendering and Routing

```php
it('can render resource list page', function () {
    $this->get(PostResource::getUrl('index'))->assertSuccessful();
});
```

##### Table Content

```php
use function Pest\Livewire\livewire;

it('displays records in the table', function () {
    $posts = Post::factory()->count(10)->create();

    livewire(PostResource\Pages\ListPosts::class)
        ->assertCanSeeTableRecords($posts);
});
```

#### Testing Create Pages

##### Rendering

```php
it('can render create page', function () {
    $this->get(PostResource::getUrl('create'))->assertSuccessful();
});
```

##### Form Submission

```php
use function Pest\Livewire\livewire;

it('can create new records', function () {
    $newData = Post::factory()->make();

    livewire(PostResource\Pages\CreatePost::class)
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
            'author_id' => $newData->author->getKey(),
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Post::class, [
        'title' => $newData->title,
        'content' => $newData->content,
    ]);
});
```

##### Validation

```php
use function Pest\Livewire\livewire;

it('validates required fields', function () {
    livewire(PostResource\Pages\CreatePost::class)
        ->fillForm([
            'title' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});
```

#### Testing Edit Pages

##### Rendering

```php
it('can render edit page', function () {
    $post = Post::factory()->create();

    $this->get(PostResource::getUrl('edit', [
        'record' => $post,
    ]))->assertSuccessful();
});
```

##### Loading Existing Data

```php
use function Pest\Livewire\livewire;

it('loads correct data in the form', function () {
    $post = Post::factory()->create();

    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getRouteKey(),
    ])
        ->assertFormSet([
            'title' => $post->title,
            'content' => $post->content,
            'author_id' => $post->author->getKey(),
        ]);
});
```

##### Updating Records

```php
use function Pest\Livewire\livewire;

it('can save updated data', function () {
    $post = Post::factory()->create();
    $newData = Post::factory()->make();

    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getRouteKey(),
    ])
        ->fillForm([
            'title' => $newData->title,
            'content' => $newData->content,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($post->refresh())
        ->title->toBe($newData->title)
        ->content->toBe($newData->content);
});
```

##### Testing Actions

```php
use Filament\Actions\DeleteAction;
use function Pest\Livewire\livewire;

it('can delete records', function () {
    $post = Post::factory()->create();

    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($post);
});

it('hides delete action for unauthorized users', function () {
    $post = Post::factory()->create();

    livewire(PostResource\Pages\EditPost::class, [
        'record' => $post->getRouteKey(),
    ])
        ->assertActionHidden(DeleteAction::class);
});
```

### Testing Relation Managers

#### Rendering

```php
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use function Pest\Livewire\livewire;

it('can render relation manager', function () {
    $category = Category::factory()
        ->has(Post::factory()->count(10))
        ->create();

    livewire(CategoryResource\RelationManagers\PostsRelationManager::class, [
        'ownerRecord' => $category,
        'pageClass' => EditCategory::class,
    ])
        ->assertSuccessful();
});
```

#### Related Records

```php
use App\Filament\Resources\CategoryResource\Pages\EditCategory;
use function Pest\Livewire\livewire;

it('displays related records', function () {
    $category = Category::factory()
        ->has(Post::factory()->count(10))
        ->create();

    livewire(CategoryResource\RelationManagers\PostsRelationManager::class, [
        'ownerRecord' => $category,
        'pageClass' => EditCategory::class,
    ])
        ->assertCanSeeTableRecords($category->posts);
});
```

Effective FilamentPHP testing ensures that your admin panels work correctly, forms save data properly, and permissions are correctly enforced. Follow these patterns to build a robust test suite for your FilamentPHP application.
