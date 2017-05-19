<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Impresora;
use Laracasts\Flash\Flash;
class ImpresorasController extends Controller
{
     function __construct() {
         $this->middleware('auth');
         $this->middleware('context');
         $this->middleware('permission:desactivar-impresoras', ['only' => ['destroy']]);
         $this->middleware('permission:editar-impresoras', ['only' => ['edit', 'update']]);
         $this->middleware('permission:crear-impresoras', ['only' => ['create', 'store']]);

        parent::__construct();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    
    public function index()
    {
        $impresoras= Impresora::all();
        return view("impresoras.index")->withImpresoras($impresoras);
        
     
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view("impresoras.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate(
             $request,[
                    'mac'=>'required|unique:sca.impresoras,mac|min:12',
                    'marca'=>'required',
                    'modelo'=>'required',
               
                    ]
                   );
      
      
       Impresora::create($request->all());
       Flash::success('¡IMPRESORA CREADA CORRECTAMENTE!');
       return redirect()->route('impresoras.index');
     
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $impresora=Impresora::find($id);
        return view("impresoras.Show")->with("impresora",$impresora);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $impresora=Impresora::find($id);
    
        return view("impresoras.edit")->with("impresora",$impresora);
       
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
        $this->validate($request, [   
            'mac' => 'required|unique:sca.impresoras,mac,'.$request->route('impresoras').',id|min:12',
            'marca' => 'required', 
            'modelo' => 'required', 
        ]);
        
        $impresora = Impresora::find($id);
        $impresora->update($request->all()); 
        Flash::success('¡IMPRESORA ACTUALIZADA CORRECTAMENTE!');
        return redirect()->route('impresoras.show', $impresora);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
       $impresora=Impresora::find($id);
       if($impresora->estatus==1){
           $impresora->estatus=0;
           $impresora->elimino=auth()->user()->idusuario;
           $impresora->motivo=$request->motivo;
           $impresora->save();
           Flash::success('¡IMPRESORA ELIMINADA CORRECTAMENTE!');

       }else{
           $impresora->estatus=1;
           $impresora->elimino=auth()->user()->idusuario;
           $impresora->motivo=null;
           $impresora->save();
           Flash::success('¡IMPRESORA ACTIVADA CORRECTAMENTE!');
       }


         return redirect()->route('impresoras.index');
    }
}
