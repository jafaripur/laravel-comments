# Laravel Commenting system

Persistant, application-wide commenting system for Laravel.

## Installation

1. `composer require jafaripur/laravel-comment`
2. Add `jafaripur\LaravelComments\ServiceProvider` to the array of providers in `config/app.php`.
3. Publish the config file by running `php artisan vendor:publish` . The config file will give you control over which storage engine to use as well as some storage-specific comments.
4. Optional: add `'Comments' => 'jafaripur\LaravelComments\Facade'` to the array of aliases in `config/app.php`.

## Usage

You can either access the comments store via its facade or inject it by type-hinting towards the abstract class `jafaripur\LaravelComments\CommentStore`.

The read function Closure give field list which want to add to query condition.

```php
<?php
Comment::read(function($fields) use ($item){
    $fields->namespace = $item['namespace'];
    $fields->threadId = $item['thread_id'];
    $fields->parentId = $item['parent_id'];
    $fields->approved = $item['approved'];
    $fields->userId = $item['user_id'];
});
Comment::save(function($fields) use ($item){
    $fields->namespace = $item['namespace'];
    $fields->threadId = $item['thread_id'];
    $fields->content = $item['content'];
    $fields->parentId = $item['parent_id'];
    $fields->approved = $item['approved'];
    $fields->userId = $item['user_id'];
});
Comment::delete($id);
Comment::approve($id);
Comment::unapprove($id);
?>
```

### Database storage

#### Using Migration File

If you use the database store you need to run `php artisan migrate --path=vendor/jafaripur/laravel-comment/src/migrations` to generate the table.

#### Example

If you need more fine-tuned control over which data gets queried, you can use the `setConstraint` method which takes a closure with two arguments:

- `$query` is the query builder instance
- `$insert` is a boolean telling you whether the query is an insert or not. If it is an insert, you usually don't need to do anything to `$query`.

```php
<?php
Comment::setConstraint(function($query, $insert) {
    if ($insert) return;
    $query->where(/* ... */);
});
?>
```

### Custom stores

This package uses the Laravel `Manager` class under the hood, so it's easy to add your own custom session store driver if you want to store in some other way. All you need to do is extend the abstract `CommentStore` class, implement the abstract methods and call `Comment::extend`.

```php
<?php
class MyStore extends jafaripur\LaravelComments\CommentStore {
    // ...
}
Comment::extend('mystore', function($app) {
    return $app->make('MyStore');
});
?>
```

## Contact

Open an issue or request pull on GitHub if you have any problems or suggestions.