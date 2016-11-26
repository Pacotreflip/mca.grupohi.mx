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
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\UserPresenter;


class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use AuthenticatableIntranetUser, Authorizable, CanResetPassword, PresentableTrait;

    protected $table = 'igh.usuario';
    protected $primaryKey = 'idusuario';
    protected $fillable = ['usuario', 'nombre', 'correo', 'clave'];
    protected $hidden = ['clave', 'remember_token'];
    protected $presenter = UserPresenter::class;
    public $timestamps = false;

    public function proyectos() {
        return $this->belongsToMany(Models\Proyecto::class, 'sca_configuracion.usuarios_proyectos', 'id_usuario_intranet', 'id_proyecto')
                ->where('sca_configuracion.proyectos.nuevo_esquema', '=', '1');
    }
    
    public function rutas() {
        return $this->hasMany(Models\Ruta::class, 'Registra');
    }
}
