# Advanced Usage

## Multiple Operation Classes, One Database Table

Depending on the complexity of your operations, you may have multiple operation classes that have the exact same columns as another operation class.
Instead of having multiple tables that are exactly the same, you can opt to create one database table with the necessary columns and then set the
`protected $table` property on your `Operation` classes to point at that table.

```php
<?php

namespace App\Operations;

use DealerInspire\Operations\Operation;

class FirstTable extends Operation
{
    protected $table = 'operations_table';

    public function run()
    {
        //
    }
}
```

```php
<?php

namespace App\Operations;

use DealerInspire\Operations\Operation;

class SecondTable extends Operation
{
    protected $table = 'operations_table';

    public function run()
    {
        //
    }
}
```

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
