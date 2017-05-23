<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;

class TagModel extends Model
{
    //
    protected $connection = 'sca';
    protected $table = 'tags';
    protected $primaryKey = 'id';

    protected $fillable = ["uid","id_proyecto","registro"];

    public $timestamps = false;
}
