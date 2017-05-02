<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_1 extends User
{
    protected $connection = 'sca';

    public function scopeChecadores($query) {
        return $query->whereHas('roles', function($q) {
            $q->where('roles.name', 'checador');
        });
    }
}
