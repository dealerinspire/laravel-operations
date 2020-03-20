# Ways to Schedule Operations

There are three ways you can run an operation. All of these exist as static functions on your operation classes, and should be called statically.

Currently it is not supported to create an instance of your operation and run it by calling these functions directly from the instance. 

## `schedule()`

We've already talked about using `schedule()` in [Creating Your First Operation](/docs/first-operation.md) adn [How to Queue Operations](/docs/queueing.md).
Whenever call `schedule()`, you need to pass in a Carbon object with the time you want the operation to run, and optionally you can pass in some additional
data for the operation to use.

## `dispatch()`

If you want to quickly create an operation that will run the very next time your Operator runs, you can use `dispatch()`. It doesn't accept a Carbon instance
and instead creates your operation with a timestamp with the current time. You can still pass in an array of attributes.

## `dispatchNow()`

If you do not want to queue an operation, you can use the `dispatchNow()` function which will run your operation synchronously. It will not run on queue or a
connection, so those properties will be ignored if they are set on the operation class.

Next: [Handling Failures Gracefully](/docs/failing.md)
