<?php

namespace Modules\Tenants;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;


class ManagerTenant
{
    public function setConnection($company){
        DB::purge('tenant'); //limpa dados de conexao atual

        //seta novos dados
        config()->set('database.connections.tenant.host', $company->db_host);
        config()->set('database.connections.tenant.port', $company->db_port);
        config()->set('database.connections.tenant.database', $company->db_name);
        config()->set('database.connections.tenant.username', $company->db_username);
        config()->set('database.connections.tenant.password', $company->db_password);

        DB::reconnect('tenant');

        Schema::connection('tenant')->getConnection()->reconnect();

    }

    public function domainIsMain(){
        return request()->getHost() == config('tenant.domain_main');
    }
}
