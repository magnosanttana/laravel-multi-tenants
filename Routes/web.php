<?php

Route::get('/tenant', function () {
    dd(config('database.connections.tenant.host'), config('database.connections.tenant.port'));
    echo 'tenant';
});

$this->get('404-tenant', function(){
    return 'Companhia não encontrada';
})->name('404-tenant');

$this->get('tenant/me', function(){
    $nome = session('company')->cliente ?? 'MATRIZ';
    return "<h1>".$nome."</h1>";
});
