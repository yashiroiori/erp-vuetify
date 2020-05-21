<?php

namespace Modules\Role\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class InstallRoleModuleCmd extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'admin:module-role:install { --force }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Module Role';

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
        Artisan::call('module:seed Role');
    }

}
