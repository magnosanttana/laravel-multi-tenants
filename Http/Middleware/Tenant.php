<?php

namespace Modules\Tenants\Http\Middleware;

use Closure;
use Modules\Tenants\Models\Company;
use Modules\Tenants\ManagerTenant;

class Tenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $manager =  app(ManagerTenant::class);
        if(!$manager->domainIsMain()){
            $company = $this->getCompany($request->getHost());
            if(!$company && $request->url() != route('404-tenant')){
                return redirect()->route('404-tenant');
            }else if($request->url() != route('404-tenant')){
                $manager->setConnection($company);
                $this->setSessionCompany($company);
            }
        }
        return $next($request);

    }

    public function getCompany($host){
        return Company::where('dominio', $host)->first();
    }

    public function setSessionCompany($company){
        session()->put('company', $company);
    }
}
