# Use Shorter and More Readable Syntax

Use Laravel's shorthand syntax where possible to make your code more concise and expressive.

## Examples

| Common syntax                                                          | Shorter and more readable syntax                                       |
| ---------------------------------------------------------------------- | ---------------------------------------------------------------------- |
| `Session::get('cart')`                                                 | `session('cart')`                                                      |
| `$request->session()->get('cart')`                                     | `session('cart')`                                                      |
| `Session::put('cart', $data)`                                          | `session(['cart' => $data])`                                           |
| `$request->input('name'), Request::get('name')`                        | `$request->name, request('name')`                                      |
| `return Redirect::back()`                                              | `return back()`                                                        |
| `is_null($object->relation) ? null : $object->relation->id`            | `optional($object->relation)->id` (in PHP 8: `$object->relation?->id`) |
| `return view('index')->with('title', $title)->with('client', $client)` | `return view('index', compact('title', 'client'))`                     |
| `$request->has('value') ? $request->value : 'default';`                | `$request->get('value', 'default')`                                    |
| `Carbon::now(), Carbon::today()`                                       | `now(), today()`                                                       |
| `App::make('Class')`                                                   | `app('Class')`                                                         |
| `->where('column', '=', 1)`                                            | `->where('column', 1)`                                                 |
| `->orderBy('created_at', 'desc')`                                      | `->latest()`                                                           |
| `->orderBy('age', 'desc')`                                             | `->latest('age')`                                                      |
| `->orderBy('created_at', 'asc')`                                       | `->oldest()`                                                           |
| `->select('id', 'name')->get()`                                        | `->get(['id', 'name'])`                                                |
| `->first()->name`                                                      | `->value('name')`                                                      |

Laravel provides many helper functions and shorthand syntax options that make your code more concise and readable. Using these shortcuts not only reduces the amount of code you need to write but also makes your intentions clearer. Prefer these shorter forms when they're available, but don't sacrifice clarity for brevity.
