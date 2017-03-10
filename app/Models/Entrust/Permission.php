<?php

namespace App\Models\Entrust;

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    protected $connection = 'sca';
}