<?php

namespace Modules\Tenants\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'identificacao', 'cliente', 'email', 'dominio', 'db_type', 'db_host',
        'db_port', 'db_name', 'db_username', 'db_password', 'principal'
    ];
}
