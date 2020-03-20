# Advanced Usage

## `queue` Property

If you have multiple queues in your app, some operations may need to run on a few of these queues. You can specify a `public $queue` property on your operation,
the value of which will be used to specify the name of the queue it will run on.

```php
<?php

namespace App\Operations;

use DealerInspire\Operations\Operation;

class HighPriorityQueueOperation extends Operation
{
    public $queue = 'high-priority';

    public function run()
    {
        //
    }
}
```

## `queueConnection` Property

If you have multiple queue connections in your app, some operations may need to run on different connections. You can specify a `public $queueConnection`
property on your operation, the value of which will be used to specify the name of the connection it will run on.

```php
<?php

namespace App\Operations;

use DealerInspire\Operations\Operation;

class RedisQueueOperation extends Operation
{
    public $queueConnection = 'redis';

    public function run()
    {
        //
    }
}
```

## Custom Horizon Tags

By default, operations are tagged with `operation` and `{Operation::class}:{id}`, which are used by Horizon to give you the ability to better track the operations
that your system is processing. If you want to define your own custom tags, override the `tags()` function and return an array of strings. These strings will be
the tags you see in Horizon.

```php
<?php

namespace App\Operations;

use DealerInspire\Operations\Operation;

class TaggedOperation extends Operation
{
    public function run()
    {
        //
    }

    public function tags()
    {
        return [
            'custom-tags',
            'foobar',
        ];
    }
}
```
