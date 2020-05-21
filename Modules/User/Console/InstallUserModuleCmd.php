<?php

namespace Modules\User\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallUserModuleCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:module-user:install { --force }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Module User';

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
    public function handle()
    {
        $this->info('Seeding data into the database');
        Artisan::call('module:seed User');
    }

}
