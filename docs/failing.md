# Handling Failures Gracefully

Unfortunately, sometimes background jobs fail, and this includes operations. Even if your code is completely bug free, external services and databases can go down. Handling failures gracefully is important in any situation, but it's especially important in background jobs.

To help you fail more gracefully an operation has two functions: `stop()` and `cancel()`.

## `stop()`

Stopping an operation is for when something has happened which means you won't be able to finish the job, but you want to try again later. This is useful if you are calling an API and get some sort of `500` error where the only choice is to wait and try again later.

Whenever you call `stop()` on an operation it will throw an exception (thus halting the code in your operation's `run` function) which is then immediately caught by the job that is running your operation. The job will set your operation back to a state where it is ready to run so that it can get picked up by the Operator again. The job that is running your operation will finish successfully, which means that it won't retry the job multiple times.

## `cancel()`

Canceling an operation is for when you want to stop running your operation _and stop it from trying again._ This can be useful if the operation has failed catastrophically and will never be able to complete, but it can also be useful if your operation was scheduled to run but is no longer applicable.

Similar to `stop()`, `cancel()` will throw an exception that is immediately caught by the job running the operation. The only difference is that instead of setting the operation to a state where it get queued again by the Operator, the operation is deleted. The job running it finishes successfully, so that it won't retry multiple times.

## Other types of exceptions

Of course, you can simply throw whatever kind of exception you want within an operation and it won't be caught like the exceptions from `stop()` and `cancel()`. Other exceptions will go all the way up to your exception handler and can be handled or sent to a bug tracking tool like any other exception.

If an unhandled exception is encountered, the operation job will retry up to whatever number of tries your workers are using, just like any other job. If you keep track of which jobs have failed, once the maximum number of tries has been exceeded you will be able to see the failed `OperationJob` there along with your other jobs.

If the operation job was not able to successfully run the operation and an exception is still being thrown, your operation will become **stuck**. This simply means that the operation has a `started_run_at` timestamp set but it is not actually being run. Because the Operator will not start running an operation that has a `should_run_at` timestamp set, the operation will never finish running.

This is why it is important to properly `stop()` or `cancel()` an operation if you are aware of an error case that you want to handle automatically.

Next: [Advanced Usage](/docs/advanced.md)
