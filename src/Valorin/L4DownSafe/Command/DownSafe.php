<?php namespace Valorin\L4DownSafe\Command;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;

class DownSafe extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'down:safe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Puts the queue into maintenance mode alongside the application.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // Check not 'sync'
        if (Config::get('queue.default') == "sync") {
            $this->error("Sorry, 'sync' queue driver not supported!");
            return;
        }

        // Push job onto queue
        Queue::push(function($job) {

            // Take Application down.
            touch(Config::get('app.manifest').'/down');

            // Add flag and log message
            Log::info("Application is down, pausing queue while maintenance happens.");

            // Loop, waiting for app to come back up
            while (App::isDownForMaintenance()) {
                echo ".";
                sleep(5);
            }

            // App is back online, kill worker to restart daemon.
            Log::info("Application is up, rebooting queue.");
            $job->delete();
            die();
        });

        // Wait until shutdown file exists
        while (!App::isDownForMaintenance()) {
            sleep(1);
        }

        $this->info("Application successfully shutdown.");
    }
}
