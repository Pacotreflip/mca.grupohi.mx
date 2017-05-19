<?php

namespace App\Http\Controllers;

use App\Models\Entrust\Role;
use App\Models\Telefono;
use App\User;
use App\User_1;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserRolesController extends Controller
{

    function __construct() {
        $this->middleware('auth');
        $this->middleware('context');

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id_user, $id_role)
    {
        if($request->ajax()) {
            $user = User_1::find($id_user);
            $role = Role::find($id_role);
            $user->detachRole($role);

            $telefono = Telefono::where('id_checador', '=', $user->idusuario)->first();
            if($telefono) {
                $telefono->id_checador = null;
                $telefono->save();
            }

            return response()->json([
                'success' => true,
                'telefonos' => Telefono::NoAsignados()->get()
            ]);
        }
    }
}
