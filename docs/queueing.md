# How to Queue Operations

You can create all the operations you want and save dozens of them in your database. But unless you actually queue them when they're ready to run, not much good will come of it.

If you went through the guide to [create your first operation](/docs/first-operation.md) then you already caught a glimpse of the `Operator::queue()` facade method. This is at the core of queueing operations, so we'll go into it in a bit more depth.

The `Operator` facade just calls the `DealerInspire\Operations\Operator` class, which is where you'll find the `queue` method. If you're not a fan of facades then you can instantiate an `Operator` instance or use dependency injection to get an instance whenever you want to call `queue`. For the purposes of this document though we'll continue referencing the facade.

Whenever you call `Operator::queue()` the `Operator` looks through all of your operations to see which runs are ready to be run.

## How does it know what operations to look for?

This is why you need to register any operations that you want the operator to pick up in the `config/operations.php` file. The operator uses the `operations` array to find which tables are holding your operations.

## How does it know which operations are ready to be run?

This is where the three operation specific timestamps come into play. First, any operation which has a `started_run_at` timestamp set is not considered ready. If this timestamp is set then the operation has already been run or is currently running, so we don't want to start running the same operation twice. If the `started_run_at` timestamp is null then it moves on to check the `should_run_at` timestamp. If that timestamp is now or in the past, it means that the operation should be run. Of course, any operation that is deleted is not considered ready to be run.

So to recap, if `started_run_at` is null, if `should_run_at` is now or in the past, and the operation has not been deleted, then the operation is ready to be run.

## How does it consistently pick up operations when they are ready?

By now you might be wondering "if operations are just records in the database, how do they get into jobs when they should be run?"

That's a great question, because so far we've been triggering the `queue` function manually. That won't scale in a production environment though.

In order for operations to be useful, you're going to need some way to call the operator's `queue` function on a consistent basis. How often you call it depends on how soon you want your operations to start running after their `should_run_at` time.

If you call `Operator::queue()` every five minutes, you might have an operation with a `should_run_at` time of 4:01am that doesn't actually run until 4:05am. In most cases this is probably fine. It's recommended to use operations for scheduling jobs that need to happen a ways off into the future (hours to weeks, or longer), so a few minutes probably won't make or break your app. If punctuality is important you could call it every minute; if you must be more accurate than that, operations may not be the tool you're looking for.

## How can I call `Operator::queue()` consistently?

The first approach would be to write your own code that simply calls `Operator::queue()`.

One example of this would be to create an endpoint, which when hit queues your operations. Then you could use a service such as AWS CloudWatch Scheduled Events to hit that endpoint every nth minute.

If you're not interested in developing your own scheduling system and have the ability to schedule `cron` jobs on your server, you can use the `operations:queue` command included in this package.

You can either write your own cron that will call `php artisan operations:queue` directly, or you can use Laravel's scheduling feature by adding it to your `app\Console\Kernel@schedule` function:

```php
protected function schedule(Schedule $schedule)
{
    $schedule->command('operations:queue')->everyFiveMinutes();
}
```

_If you do use Laravel's scheduling function, don't forget to [set it up](https://laravel.com/docs/5.8/scheduling#introduction)._

## Hooking into when your operation is queued

If you ever need to do some logic or action while an event is being queued, you can use the `queue` hook function. This function is easy to abuse, so you can read more about what it is and how to avoid some potential pitfalls in [Queue Hook Function](/docs/queue-hook.md).

Next: [Ways to Schedule Operations](/docs/scheduling.md)
