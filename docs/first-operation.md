# Creating Your First Operation

After installing the package you'll no doubt want to get started by creating an operation.

In this guide, we'll imagine that we're building an app where every user has a `rank` column that just holds a string. When the user first signs up they have a "New" rank, and after one week they get upgraded to be a "Beginner". Our operation will be scheduled to run one week after the user signs up and will update that `rank` column to the "Beginner" value.

If you would like to follow along in your own project, you can create a fresh Laravel installation, set up the authentication scaffolding, and add a string `rank` column to your user model. Then follow the instructions in the [README](/README.md#installation) to install the package.

To begin, you'll need an operation model and a database table for it. You can create these easily using the `make:operation` command with a flag to also create the migration:

```bash
php artisan make:operation RankUpUserOperation -m
```

This will give you two new files: `app\Operations\RankUpUserOperation.php` and a `create_rank_up_user_operations` migration.

Because this operation is updating a column on a user model, we'll need to be able to store which user ID we need to update. Open up the migration file and add an additional `user_id` column:

```php
<?php

// Use Statements

class CreateRankUpUserOperations extends Migration
{
    public function up() {
        Schema::create('rank_up_user_operations', function (Blueprint $table) {
            $table->bigIncrements('id');

            // Custom User ID Column
            $table->unsignedBigInteger('user_id');

            // Timestamps
        });
    }
    
    // ...
}
```

You can now run `php artisan migrate` to create the table. Once that is done we can move on to writing the code in the operation.

> **Remember!** Every operation needs to have a `run` method implemented. The abstract Operation object that your Operation extends doesn't have an abstract `run` method to allow for dependency injection, so it's up to you to remember. But don't worry too much; if you forget to add it you will be reminded by an exception as soon as you try to use it.

```php
<?php

namespace App\Operations;

use App\User;
use DealerInspire\Operations\Operation;

class RankUpUserOperation extends Operation
{
    public function run()
    {
        User::where('id', $this->user_id)->update(['rank' => 'Beginner']);
    }
}
```

Simple as that! Now any time our operation is run, the user associated to this operation will get a rank of `Beginner`.

We've created our new `RankUpUserOperation` but before we can start using it we need to make sure that we register it in our `operations.php` config file. If you don't have that file yet, make sure to run the `vendor:publish` artisan command to get it. Once you have that file you just need to reference your new operation class in the `operations` array:

```php
'operations' => [
    \App\Operations\RankUpUserOperation::class,
],
```

Now that we have an operation and have it registered so that the package knows about it, we need a way to save new operations to our database. In this case, we've decided that this operation should run for each user one week after they sign up.

To accomplish this we could use an observer and listen for any time a User is created, but for the purpose of keeping it simple we'll just create an operation at the same time we create the user. To do that you should open up `app\Http\Controllers\Auth\RegisterController.php` and change the `create` function to look like the following:

```php
protected function create(array $data)
{
    $user = User::create(/* ... */);

    RankUpUserOperation::schedule(Carbon::now()->addWeek(), [
        'user_id' => $user->id,
    ]);

    return $user;
}
```

The static `schedule` function is a helpful little way to more coherently create an operation. It simply takes the time when you want the operation to run and an optional array if you have additional attributes you need to set, like our `user_id`. If you don't have any custom attributes to set then you can just leave off the array entirely. If you're not a fan of these kinds of static methods, don't worry. You can still create an instance of your operation and save it however you'd like, just so long as you remember to set `should_run_at`.

If you browse to your Laravel project now in your browser and register a new account, you should not only see a new record in the `users` table, but also a new record in the `rank_up_user_operations` table that has your new user's ID and a `should_run_at` timestamp set to one week in the future.

You could wait a week to try and see if the operation works, but since I'm rather impatient I'm going to modify my database record to have a `should_run_at` timestamp of a few minutes in the future. You could also remove the `addWeek` call on the Carbon instance and create another new user if you don't want to tinker around in your database.

In the next guide we'll be going over how you can actually get these operations to run when they're ready. For right now I'm going to use artisan tinker to do it, by calling the Operator's `queue` method through the facade.

```bash
php artisan tinker
```

```
DealerInspire\Operations\Facades\Operator::queue()
```

If your queue driver is set to `sync` then everything should finish, well, synchronously. If you have your driver set to anything else make sure to run `php artisan queue:work` or whatever else you need to do to handle the job that gets created by running the `queue` command.

After the job has finished processing you should see that the Operation record in the database has timestamps set for `started_run_at` and `finished_run_at`, and the User record's `rank` has been updated to "Beginner".

Next: [How to Queue Operations](/docs/queueing.md)
