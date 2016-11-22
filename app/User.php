<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Ghi\Core\App\Auth\AuthenticatableIntranetUser;


class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use AuthenticatableIntranetUser, Authorizable, CanResetPassword;

    protected $table = 'igh.usuario';
    protected $primaryKey = 'idusuario';
    protected $fillable = ['usuario', 'nombre', 'correo', 'clave'];
    protected $hidden = ['clave', 'remember_token'];
    public $timestamps = false;
    
    public function proyectos() {
        return $this->belongsToMany(Models\Proyecto::class, 'sca_configuracion.usuarios_proyectos', 'id_usuario_intranet', 'id_proyecto');
    }
}