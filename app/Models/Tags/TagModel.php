<?php

namespace App\Models\Tags;

use Illuminate\Database\Eloquent\Model;

class TagModel extends Model
{
    // Datos de inicio de configuracio
    protected $connection = 'sca';
    protected $table = 'tags';
    protected $primaryKey = 'id';

    //Informacion de campos llenables
    protected $fillable = ["uid","id_proyecto","registro"];

    // Validacion de registro de TimeStamps
    public $timestamps = false;
}
