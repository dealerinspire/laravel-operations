# Laravel Operations

Operations allow you to schedule your jobs in a programmatic, database-driven way. By creating an operation record in your database and then calling the Operator to queue any operations that are ready to be run, you can schedule tasks for execution in the far future and keep track of every operation that has already been run.

## Installation

Install the package by requiring it with Composer.

```bash
composer require dealerinspire/laravel-operations
```

The service provider will get registered automatically, but you will need to publish the config file.

```bash
php artisan vendor:publish --provider="DealerInspire\Operations\OperationServiceProvider"
```

## Usage

Here is an example of a very simple Operation:

```php
class ExampleOperation extends Operation
{
    public function run()
    {
        // Logic goes in here to be executed when the operation is run.
    }
}
```

_If you would like to use dependency injection, you can add type-hinted parameters to your `run` function to be injected at runtime._

Operations are Eloquent models, and can have whatever columns on them that you want. The only requirement is that they have three additional timestamp columns, `should_run_at`, `started_run_at`, and `finished_run_at`.

To make sure that the Operator knows about all of your operations you should add the classname to the `config\operations.php` file.

```php
'operations' => [
    \App\Operations\ExampleOperation::class,
],
```

To put any operations that are ready to run into your job queue, you should call the Operator's `queue` function.

```php
// Using the facade.

use DealerInspire\Operations\Facades\Operator;

// ...

Operator::queue();
```

```php
// Using an Operator instance.

use DealerInspire\Operations\Operator;

// ...

$operator = new Operator();
$operator->queue();
```

Any operation that has a `should_run_at` timestamp which is in the past and has a null `started_run_at` value (and also hasn't been deleted) will be put into your job queue.

An operation's `started_run_at` timestamp will be set to the current time as soon as it is placed into your queue (not when the job actually begins getting processed by a worker). Once the operation has been run by a worker the `finished_run_at` timestamp will be set.

You can quickly create new operations (which will be placed in your `App\Operations` directory) by running the `make:operation`.

```bash
php artisan make:operation MyNewOperation
```

There is also a `--migration` flag that you can use to create a migration with the necessary timestamps.

## Documentation

More in-depth information on best practices and how to use operations effectively.

 - [Creating Your First Operation](/docs/first-operation.md)
 - [How to Queue Operations](/docs/queueing.md)
 - [Handling Failures Gracefully](/docs/failing.md)

## License

MIT © [Dealer Inspire](https://www.dealerinspire.com/)
