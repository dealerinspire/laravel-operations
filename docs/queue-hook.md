# Queue Hook Function

Before you use the `queue` hook, you need to know about some potential pitfalls you can fall into if you're not careful.

The biggest issue you could run into is making your hook resource intensive. The Operator calls an operation's `queue` function synchronously (if it exists) for every operation that's ready to run. If you make external API calls, the cron job that handles queueing all of your operations will become unbearably slow. The `queue` hook should be reserved for things that you need to happen before an operation is ever placed into the queue.

To avoid most pitfalls, try placing everything into the `run` function and only move it into `queue`  when you have issues with it.

## How it Works

Now that you have an operation where you're certain that the `queue` hook is what you need, we'll dive into how to implement it.

Within your operation's class where you implemented the `run` function, add a `public` `queue` function:

```php
<?php

namespace App\Operations;

use DealerInspire\Operations\Operation;

class RankUpUserOperation extends Operation
{
    public function queue() {
        // Do whatever you need to do.
    }

    public function run() { /* ... */ }
}
```

Now, whenever the Operator queues a `RankUpUserOperation` it will call your custom `queue` function before it adds the operation to the job queue.

## What Is a Good Use Case?

Keeping `queue` short and sweet is important, so what can you _actually_ do with it?

The most generally applicable use case would be to dispatch an event that your operation has been queued. This is especially useful if you have several unrelated things that need to happen when an operation is queued. Dispatching an event allows your `queue` function to run quickly, ensuring that your Operator doesn't get backed up trying to execute slow functions.

If you use Laravel's [broadcasting](https://laravel.com/docs/5.8/broadcasting) functionality, dispatching a notification to your front-end application when an operation is queued can allow your users to have dynamic insights into processes that they might be waiting for. Dispatching a notification letting them know that an operation has been placed in the queue, and then dispatching more notifications when it runs, can give your users confidence that your application is doing what it says it will do.
