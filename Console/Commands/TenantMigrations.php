<?php

namespace Modules\Tenants\Console\Commands;

use Illuminate\Console\Command;
use Modules\Tenants\ManagerTenant;
use Modules\Tenants\Models\Company;
use Illuminate\Support\Facades\Artisan;

class TenantMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenants:migrations';

    private $tenant;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Migrations Tenants';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ManagerTenant $tenant)
    {
        $this->tenant = $tenant;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Company::all();

        foreach($companies as $company){
            $this->tenant->setConnection($company);
            $this->info("Connecting Company {$company->cliente}");
            Artisan::call('migrate', [
                    '--force' => true,
                    '--path' => '/database/migrations/tenant'
            ]);
            $this->info("End connecting Company {$company->cliente}");
            $this->info('-------------------------------------------');
        }
    }
}
