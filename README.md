Laravel 4 Down Safe
=================

Artisan command for switching a Laravel 4 queue worker into maintenance mode alongside the application safely.

When running `./artisan down:safe`, it will add a job into the queue and wait. When this job is processed, the application is taken down into maintenance mode and the queue worker remains looping that job - however command will finish so you can proceed with maintenance. Finally, when the application is brought back up with `./artisan up`, the queue worker is killed so it can reload the application environment with fresh code after the maintenance.

If running `./artisan queue:listen`, the listener will continue to operate throughout the process seamlessly. However, `./artisan queue:work --daemon` will die and need to be restarted.

**Important:** It only supports a single queue worker. For something more complicated, you will need a more powerful solution to manage workers.

Installation
------------

Add the package to your application with composer:

```
composer require "valorin/l4-down-safe:dev-master"
```

Add the `L4DownSafeServiceProvider` service provider to the `providers` list in `./app/config/app.php`:

```
'providers' => array(
    ...
    'Valorin\L4DownSafe\L4DownSafeServiceProvider',
),
```

Usage
-----

When ready to switch the application into maintenance mode, run:

```
./artisan down:safe
```

When the script finishes executing, the application is in maintenance mode. When ready to take it back up, run:

```
./artisan up
```
