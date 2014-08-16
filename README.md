Laravel 4 Down Safe
=================

Artisan command for switching a Laravel 4 queue worker into maintenance mode alongside the application safely.

When running `./artisan down:safe`, it will add a job into the queue and wait. When this job is processed, the application is taken down into maintenance mode and the queue worker remains looping that job - however command will finish so you can proceed with maintenance. Finally, when the application is brought back up with `./artisan up`, the `./artisan queue:restart` command is called and the job finishes, so the worker can listen to the restart command and stop (so it can restart).

If running `./artisan queue:listen`, the listener will continue to operate throughout the process seamlessly. However, `./artisan queue:work --daemon` will stop and need to be restarted.

**Important:** It only supports a single queue worker. For something more complicated, you will need a more powerful solution to manage workers.

Installation
------------

Add the package to your application with composer:

```
composer require "valorin/l4-down-safe:~1.1"
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

Version History
---------------

`v1.1.1` -- Switched to simply take down the application if `sync` queue specified.

`v1.1.0` -- Requires Laravel v4.2.5, and uses the `./artisan queue:restart` command to trigger a daemon worker restart.

`v1.0.0` -- Laravel v4.2.0+, using a manual `die();` on the worker when the application comes back up.
