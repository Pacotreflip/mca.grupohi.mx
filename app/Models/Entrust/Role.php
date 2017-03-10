<?php

namespace App\Models\Entrust;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $connection = 'sca';
}