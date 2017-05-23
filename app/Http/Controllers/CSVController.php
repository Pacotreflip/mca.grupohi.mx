<?php

namespace App\Http\Controllers;

use App\CSV\CSV;
use App\Models\Camion;
use App\Models\CentroCosto;
use App\Models\ConfiguracionDiaria\Configuracion;
use App\Models\Empresa;
use App\Models\Etapa;
use App\Models\FDA\FDABancoMaterial;
use App\Models\FDA\FDAMaterial;
use App\Models\Marca;
use App\Models\Material;
use App\Models\Operador;
use App\Models\Origen;
use App\Models\Ruta;
use App\Models\Sindicato;
use App\Models\Tarifas\TarifaMaterial;
use App\Models\Tarifas\TarifaPeso;
use App\Models\Tiro;
use App\Models\Impresora;
use App\Models\Telefono;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\User_1;
use App\Models\Entrust\Permission;
use App\Models\Entrust\Role;
use App\Models\Transformers\UsuarioPerfilesTransformer;
use League\Flysystem\Plugin\ForcedRename;
use function MongoDB\BSON\toJSON;

class CSVController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    public function rutas() {
        $headers = ['Clave', 'Origen', 'Tiro', 'Tipo de Ruta', '1er. KM', 'KM Subsecuentes', 'KM Adicionales', 'KM Total', 'Tiempo Minimo', 'Tiempo Tolerancia', "Estatus", "Registró", "Fecha y Hora Registro","Desactivo", "Fecha y Hora de Desactivación","Motivo de Desactivación"];
        $items = Ruta::leftJoin('origenes', 'rutas.IdOrigen', '=', 'origenes.IdOrigen')
            ->leftJoin('tiros', 'rutas.IdTiro', '=', 'tiros.IdTiro')
            ->leftJoin('tipo_ruta', 'rutas.IdTipoRuta', '=', 'tipo_ruta.IdTipoRuta')
            ->leftJoin('cronometrias', 'rutas.IdRuta', '=', 'cronometrias.IdRuta')
            ->leftJoin('igh.usuario as user_registro', 'rutas.usuario_registro', '=', 'user_registro.idusuario')
            ->leftJoin('igh.usuario as user_desactivo', 'rutas.usuario_desactivo', '=', 'user_desactivo.idusuario')
            ->select(
                DB::raw("CONCAT(rutas.Clave,rutas.IdRuta)"),
                "origenes.Descripcion as Origen",
                "tiros.Descripcion as Tiro",
                "tipo_ruta.Descripcion as Tipo",
                "rutas.PrimerKm",
                "rutas.KmSubsecuentes",
                "rutas.KmAdicionales",
                "rutas.TotalKM",
                "cronometrias.TiempoMinimo",
                "cronometrias.Tolerancia",
                DB::raw("IF(rutas.Estatus = 1, 'ACTIVA', 'INACTIVA')"),
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)"),
                "rutas.created_at",
                DB::raw("CONCAT(user_desactivo.nombre, ' ', user_desactivo.apaterno, ' ', user_desactivo.amaterno)"),
                DB::raw("IF(rutas.Estatus = 1, '', rutas.updated_at)"),
                "rutas.motivo")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('rutas');
    }

    public function origenes()
    {
        $headers = ["Clave", "Tipo", "Descripción", "Estatus", "Registró", "Fecha y Hora Registro", "Desactivo", "Fecha y Hora de Desactivación", "Motivo de Desactivación"];

        $items = Origen::
        leftJoin('igh.usuario as user_registro', 'origenes.usuario_registro', '=', 'user_registro.idusuario')
            ->leftJoin('igh.usuario as user_desactivo', 'origenes.usuario_desactivo', '=', 'user_desactivo.idusuario')
            ->leftJoin('tiposorigenes', 'origenes.IdTipoOrigen', '=', 'tiposorigenes.IdTipoOrigen')
            ->select(
                DB::raw("CONCAT(origenes.Clave,origenes.IdOrigen)"),
                "tiposorigenes.Descripcion as Tipo",
                "origenes.Descripcion",
                "origenes.Estatus",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)"),
                "origenes.created_at",
                DB::raw("CONCAT(user_desactivo.nombre, ' ', user_desactivo.apaterno, ' ', user_desactivo.amaterno)"),
                DB::raw("IF(origenes.Estatus = 1, '', origenes.updated_at)"),
                "origenes.motivo")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('origenes');
    }

    public function tiros()
    {
        $headers = ["Clave", "Descripción", "Estatus", "Registró", "Fecha y Hora Registro", "Desactivo", "Fecha y Hora de Desactivación", "Motivo de Desactivación"];
        $items = Tiro::
        leftJoin('igh.usuario as user_registro', 'tiros.usuario_registro', '=', 'user_registro.idusuario')
            ->leftJoin('igh.usuario as user_desactivo', 'tiros.usuario_desactivo', '=', 'user_desactivo.idusuario')
            ->select(
                DB::raw("CONCAT(tiros.Clave,tiros.IdTiro)"),
                "tiros.Descripcion",
                "tiros.Estatus",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)"),
                "tiros.created_at",
                DB::raw("CONCAT(user_desactivo.nombre, ' ', user_desactivo.apaterno, ' ', user_desactivo.amaterno)"),
                DB::raw("IF(tiros.Estatus = 1, '', tiros.updated_at)"),
                "tiros.motivo")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('tiros');
    }

    public function camiones()
    {
        $headers = ["Economico", "Sindicato", "Empresa", "Placas del Camión", "Placas de la Caja", "Marca", "Modelo", "Propietario", "Operador", "Aseguradora", "Poliza de Seguro", "Vigencia Seguro", "Cubicación Real", "Cubicación Para Pago", "Estatus"];
        $items = Camion::leftJoin('operadores', 'camiones.IdOperador', '=', 'operadores.IdOperador')
            ->leftJoin('sindicatos', 'camiones.IdSindicato', '=', 'sindicatos.IdSindicato')
            ->leftJoin('empresas', 'camiones.IdEmpresa', '=', 'empresas.IdEmpresa')
            ->leftJoin('marcas', 'camiones.IdMarca', '=', 'marcas.IdMarca')
            ->select(
                "camiones.Economico",
                "sindicatos.Descripcion as Sindicato",
                "empresas.razonSocial as Empresa",
                "camiones.Placas",
                "camiones.PlacasCaja",
                "marcas.Descripcion as Marca",
                "camiones.Modelo",
                "camiones.Propietario",
                "operadores.Nombre",
                "camiones.Aseguradora",
                "camiones.PolizaSeguro",
                "camiones.VigenciaPolizaSeguro",
                "camiones.CubicacionReal",
                "camiones.CubicacionParaPago",
                "camiones.Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('camiones');
    }

    public function materiales()
    {
        $headers = ["ID", "Descripción", "Estatus", "Registro", "Fecha y Hora de Registro", "Desactivó", "Fecha y Hora de Desactivación", "Motivo de Desactivación"];
        $items = Material::leftJoin('igh.usuario as user_registro', 'materiales.usuario_registro', '=', 'user_registro.idusuario')
            ->leftJoin('igh.usuario as user_desactivo', 'materiales.usuario_desactivo', '=', 'user_desactivo.idusuario')
            ->select(
                "IdMaterial",
                "Descripcion",
                "Estatus",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)"),
                "materiales.created_at",
                DB::raw("CONCAT(user_desactivo.nombre, ' ', user_desactivo.apaterno, ' ', user_desactivo.amaterno)"),
                DB::raw("IF(materiales.Estatus = 1, '', materiales.updated_at)"),
                "materiales.motivo"
            )->get();
        $csv = new CSV($headers, $items);
        $csv->generate('materiales');
    }

    public function empresas()
    {
        $headers = ["ID", "Razón Social", "RFC", "Estatus"];
        $items = Empresa::select("IdEmpresa", "razonSocial", "RFC", "Estatus")->get();
        $csv = new CSV($headers, $items);
        $csv->generate('empresas');
    }

    public function sindicatos()
    {
        $headers = ["ID", "Descripción", "Nombre Corto", "Estatus"];
        $items = Sindicato::select("IdSindicato", "Descripcion", "NombreCorto", "Estatus")->get();
        $csv = new CSV($headers, $items);
        $csv->generate('sindicatos');
    }

    public function centros_costos()
    {
        $headers = ["ID", "Descripción", "Cuenta", "Centro de Costo Padre", "Estatus"];
        $items = CentroCosto::leftJoin("centroscosto as padres", "centroscosto.IdPadre", "=", "padres.IdCentroCosto")
            ->select(
                "centroscosto.IdCentroCosto",
                "centroscosto.Descripcion",
                "centroscosto.Cuenta",
                "padres.Descripcion as padre",
                "centroscosto.Estatus")
            ->orderBy("centroscosto.IdCentroCosto", "ASC")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('centros_costo');
    }

    public function etapas_proyecto()
    {
        $headers = ["ID", "Descripción", "Estatus"];
        $items = Etapa::select("IdEtapaProyecto", "Descripcion", "Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('etapas_proyecto');
    }

    public function fda_material()
    {
        $headers = ["ID", "Material", "Factor de Abundamiento", "Fecha y Hora Registro", "Estatus", "Registró"];
        $items = FDAMaterial::leftJoin("materiales", "factorabundamiento.IdMaterial", "=", "materiales.IdMaterial")
            ->leftJoin("igh.usuario", "factorabundamiento.Registra", "=", "igh.usuario.usuario")
            ->select(
                "factorabundamiento.IdFactorAbundamiento",
                "materiales.Descripcion as Material",
                "factorabundamiento.FactorAbundamiento",
                "factorabundamiento.TimestampAlta",
                "factorabundamiento.Estatus",
                DB::raw("CONCAT(igh.usuario.nombre, ' ', igh.usuario.apaterno, ' ', igh.usuario.amaterno)"))
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('fda_material');
    }

    public function fda_banco_material()
    {
        $headers = ["Banco", "Material", "Factor de Abundamiento", "Fecha y Hora Registro", "Estatus", "Registró"];
        $items = FDABancoMaterial::leftJoin("materiales", "factorabundamiento_material.IdMaterial", "=", "materiales.IdMaterial")
            ->leftJoin("origenes", "factorabundamiento_material.IdBanco", "=", "origenes.IdOrigen")
            ->leftJoin("igh.usuario", "factorabundamiento_material.Registra", "=", "igh.usuario.usuario")
            ->select(
                "origenes.Descripcion as Banco",
                "materiales.Descripcion as Material",
                "factorabundamiento_material.FactorAbundamiento",
                "factorabundamiento_material.TimestampAlta",
                "factorabundamiento_material.Estatus",
                DB::raw("CONCAT(igh.usuario.nombre, ' ', igh.usuario.apaterno, ' ', igh.usuario.amaterno)"))
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('fda_banco_material');
    }

    public function marcas()
    {
        $headers = ["ID", "Descripción", "Estatus"];
        $items = Marca::select("IdMarca", "Descripcion", "Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('marcas');
    }

    public function operadores()
    {
        $headers = ["ID", "Nombre", "Dirección", "Número de Licencia", "Vigencia de Licencia", "Fecha Registro", "Fecha de Baja", "Estatus"];
        $items = Operador::select("IdOperador", "Nombre", "Direccion", "NoLicencia", "VigenciaLicencia", "FechaAlta", "FechaBaja", "Estatus")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('operadores');
    }

    public function tarifas_material()
    {
        $headers = ["ID", "Material", "Primer KM", "KM Subsecuente", "KM Adicional", "Fecha y Hora Registro", "Estatus", "Registra", "Inicio Vigencia", "Fin Vigencia"];
        $items = TarifaMaterial::leftJoin('materiales', 'tarifas.IdMaterial', '=', 'materiales.IdMaterial')
            ->leftJoin('igh.usuario', 'tarifas.Registra', '=', 'igh.usuario.idusuario')
            ->select(
                "tarifas.IdTarifa",
                "materiales.Descripcion",
                "tarifas.PrimerKM",
                "tarifas.KMSubsecuente",
                "tarifas.KMAdicional",
                "tarifas.Fecha_Hora_Registra",
                "tarifas.Estatus",
                DB::raw("CONCAT(igh.usuario.nombre, ' ', igh.usuario.apaterno, ' ', igh.usuario.amaterno)"),
                "tarifas.InicioVigencia",
                "tarifas.FinVigencia")
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('tarifas_material');
    }

    public function tarifas_peso()
    {
        $headers = ["ID", "Material", "Primer KM", "KM Subsecuente", "KM Adicional", "Fecha y Hora Registro", "Estatus", "Registro"];
        $items = TarifaPeso::leftJoin('materiales', 'tarifas_peso.IdMaterial', '=', 'materiales.IdMaterial')
            ->leftJoin('igh.usuario', 'tarifas_peso.Registra', '=', 'igh.usuario.idusuario')
            ->select(
                "tarifas_peso.IdTarifa",
                "materiales.Descripcion as Material",
                "tarifas_peso.PrimerKM",
                "tarifas_peso.KMSubsecuente",
                "tarifas_peso.KMAdicional",
                "tarifas_peso.Fecha_Hora_Registra",
                "tarifas_peso.Estatus",
                DB::raw("CONCAT(igh.usuario.nombre, ' ', igh.usuario.apaterno, ' ', igh.usuario.amaterno)")
            )
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('tarifas_peso');
    }

    public function configuracion_checadores()
    {
        $headers = ['ID', 'Checador', 'Usuario Intranet', 'Origen / Tiro', 'Ubicación', 'Perfil', 'Turno', 'Fecha Y Hora de Configuración', 'Configuró'];
        $items = Configuracion::leftJoin('igh.usuario as checador', 'configuracion_diaria.id_usuario', '=', 'checador.idusuario')
            ->leftJoin('origenes', 'configuracion_diaria.id_origen', '=', 'origenes.IdOrigen')
            ->leftJoin('tiros', 'configuracion_diaria.id_tiro', '=', 'tiros.IdTiro')
            ->leftJoin('configuracion_perfiles_cat as perfiles', 'configuracion_diaria.id_perfil', '=', 'perfiles.id')
            ->leftJoin('igh.usuario as user_registro', 'configuracion_diaria.registro', '=', 'user_registro.idusuario')
            ->select(
                "configuracion_diaria.id",
                DB::raw("CONCAT(checador.nombre, ' ', checador.apaterno, ' ', checador.amaterno)"),
                "checador.usuario",
                DB::raw("IF(configuracion_diaria.tipo = 0, 'Origen', 'Tiro')"),
                DB::raw("IF(configuracion_diaria.id_origen is null, tiros.Descripcion, origenes.Descripcion)"),
                "perfiles.name",
                DB::raw("IF(configuracion_diaria.turno = 'M', 'Matutino', IF(configuracion_diaria.turno = 'v', 'Vespertino', 'NO ASIGNADO'))"),
                "configuracion_diaria.created_at",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)")
            )
            ->get();
        $csv = new CSV($headers, $items);
        $csv->generate('configuracion-checadores');
    }

    public function impresoras()
    {
        $headers = ["ID", "MAC Address", "Marca", "Modelo", "Estatus", "Registró", "Fecha y Hora Registro", "Desactivo", "Fecha y Hora de Desactivación", "Motivo de Desactivación"];
        $items = Impresora::leftjoin('igh.usuario as user_registro', 'impresoras.registro', '=', 'user_registro.idusuario')
            ->leftjoin('igh.usuario as user_elimino', 'impresoras.elimino', '=', 'user_elimino.idusuario')
            ->select(
                "impresoras.id",
                "impresoras.mac",
                "impresoras.marca",
                "impresoras.modelo",
                "impresoras.estatus",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)"),
                "impresoras.created_at",
                DB::raw("CONCAT(user_elimino.nombre, ' ', user_elimino.apaterno, ' ', user_elimino.amaterno)"),
                "impresoras.updated_at",
                "impresoras.motivo")->get();
        $csv = new CSV($headers, $items);
        $csv->generate('impresoras');
    }

    public function telefonos()
    {
        $headers = ["ID", "IMEI Teléfono", "Linea Telefónica", "Marca", "Modelo", "Estatus", "Registró", "Fecha y Hora de Registro", "Desactivó", "Fecha y Hora de Desactivación", "Motivo de Desactivación"];
        $items = Telefono::leftjoin('igh.usuario as user_registro', 'telefonos.registro', '=', 'user_registro.idusuario')
            ->leftjoin('igh.usuario as user_elimino', 'telefonos.elimino', '=', 'user_elimino.idusuario')
            ->select(
                "telefonos.id",
                "telefonos.imei",
                "telefonos.linea",
                "telefonos.marca",
                "telefonos.modelo",
                "telefonos.estatus",
                DB::raw("CONCAT(user_registro.nombre, ' ', user_registro.apaterno, ' ', user_registro.amaterno)"),
                "telefonos.created_at",
                DB::raw("CONCAT(user_elimino.nombre, ' ', user_elimino.apaterno, ' ', user_elimino.amaterno)"),
                "telefonos.updated_at",
                "telefonos.motivo")->get();

        $csv = new CSV($headers, $items);
        $csv->generate('telefonos');
    }
    public function usuario_rol()
    {
        
         $usuarios = UsuarioPerfilesTransformer::transform(User_1::with('roles')->habilitados()->get());
        $permisos = Permission::all();
        $roles = Role::with('perms')->get();

        $rol_usuario = array();
        $role_usuario_vista = array();
        $permisos_rol_vista= array();

        ////////////Rol usuario
        foreach ($usuarios as $usuario )
        {
            $rol_usuario = array();
            if(Auth::user()->hasRole('administrador-sistema')){
                array_push($rol_usuario, $usuario['id']);
            }
            array_push($rol_usuario, $usuario['nombre']);
            if (count($usuario['roles']) > 0)
            {
                foreach ($roles as $rol) {
                    $aux = false;
                    foreach ($usuario['roles'] as $rolAsignado) {
                        if ($rol->id == $rolAsignado->id) {
                            $aux = true;
                        }
                    }
                    array_push($rol_usuario, $aux);
                }
            } else
            {
                $a = 0;
                while (count($roles) > $a) {
                    array_push($rol_usuario, '');
                    $a++;
                }
            }
            $data =  $rol_usuario;
            array_push($role_usuario_vista, $data);
        }
        $headers = [];
         
        if(Auth::user()->hasRole('administrador-sistema')){
            array_push($headers, 'Id');
        }
        array_push($headers, 'Usuario');
        foreach ($roles as $rol) {
            array_push($headers, $rol->display_name);
        }
        $items = $role_usuario_vista;
        $csv = new CSV($headers,$items);
        $csv->generate('usuarios-roles');


    }

    public function rol_permiso()
    {
        $usuarios = UsuarioPerfilesTransformer::transform(User_1::with('roles')->habilitados()->get());
        $permisos = Permission::all();
        $roles = Role::with('perms')->get();

        $rol_usuario = array();
        $role_usuario_vista = array();
        $permisos_rol_vista= array();
        ///////////Rol permisos
        foreach ($roles as $rol)
        {
            $rol_permisos= array();
            array_push($rol_permisos, $rol->display_name);
            $permisoActual=DB::connection('sca')->table('sca_configuracion.permission_role')->where('role_id', '=', $rol->id)->get();
            if (count($permisoActual) > 0)
            {
                foreach ($permisos as $permiso){
                    $aux = false;
                    for($a=0;$a<count($permisoActual);$a++){
                        if($permisoActual[$a]->permission_id==$permiso->id){
                            $aux=true;
                        }
                    }
                    array_push($rol_permisos, $aux);
                }
            }
            else
            {
                $a = 0;
                while (count($permisos) > $a) {
                    array_push($rol_permisos, false);
                    $a++;
                }
            }

            $data = $rol_permisos;
            array_push($permisos_rol_vista, $data);
        }
        $headers = [];
        array_push($headers, 'Rol');
        foreach ($permisos as $permiso) {
            array_push($headers, $permiso->display_name);
        }


        $items = $permisos_rol_vista;
        $csv = new CSV($headers,$items);
        $csv->generate('roles-permisos');

       

    }

    public function usuario_permiso()
    {

        $usuarios = UsuarioPerfilesTransformer::transform(User_1::with('roles')->habilitados()->get());
        $permisos = Permission::all();
        $roles = Role::with('perms')->get();

        $rol_usuario = array();
        $role_usuario_vista = array();
        $permisos_rol_vista= array();

        $usuario_Permisos=array();
        $usuario_Permisos_vista=array();

        foreach ($usuarios as $usuario) {
            $usuario_Permisos=array();
            if(Auth::user()->hasRole('administrador-sistema')){
                array_push($usuario_Permisos, $usuario['id']);
            }
            array_push($usuario_Permisos, $usuario['nombre']);
            $permisosActuales = DB::connection('sca')->select('
                           Select distinct p.id,p.display_name FROM 
                            sca_configuracion.role_user ru
                            inner join 
                            sca_configuracion.roles rol on(ru.role_id=rol.id)
                            inner join sca_configuracion.permission_role pm on(pm.role_id=rol.id)
                            inner join sca_configuracion.permissions p on(pm.permission_id=p.id)
                            and ru.user_id=:userid
                            group by (pm.permission_id)
                            order by 1 asc', ['userid' => $usuario['id']]);

            if(count($permisosActuales)>0){
                foreach ($permisos as $permiso) {
                    $aux = false;
                    foreach ($permisosActuales as $permisosActual) {
                        if ($permiso->id == $permisosActual->id) {
                            $aux = true;
                        }
                    }
                    array_push($usuario_Permisos, $aux);
                }

            }
            else {
                $a = 0;
                while (count($permisos) > $a) {
                    array_push($usuario_Permisos, false);
                    $a++;
                }
            }
            $data = $usuario_Permisos;
            array_push($usuario_Permisos_vista, $data);

        }

        $headers = [];
        if(Auth::user()->hasRole('administrador-sistema')){
            array_push($headers, 'Id');
        }
        array_push($headers, 'Usuario');
        foreach ($permisos as $permiso) {
            array_push($headers, $permiso->display_name);
        }

        $items = $usuario_Permisos_vista;
        $csv = new CSV($headers,$items);
        $csv->generate('usuarios-permisos');

    }



}
