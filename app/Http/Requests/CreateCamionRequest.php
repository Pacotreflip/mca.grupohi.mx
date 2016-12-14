<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateCamionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'IdSindicato'   => 'required|integer|exists:sca.sindicatos,IdSindicato',
            'Propietario'   => 'required|string',
            'IdOperador'    => 'required|integer|exists:sca.operadores,IdOperador',
            'Economico'     => 'required|string|unique:sca.camiones,Economico',
            'Placas'        => 'required|string|unique:sca.camiones,Placas',
            'PlacasCaja'    => 'string',
            'IdMarca'       => 'required|integer|exists:sca.marcas,IdMarca',
            'Modelo'        => 'required|string',
            'IdBoton'       => 'integer|exists:sca.botones,IdBoton',
            'Aseguradora'   => 'string',
            'PolizaSeguro'  => 'string',
            'VigenciaPolizaSeguro'  => 'date_format:"Y-m-d"',     
            'Ancho'         => 'required|numeric',
            'Largo'         => 'required|numeric',
            'Alto'          => 'required|numeric',
            'EspacioDeGato' => 'numeric',
            'Disminucion' => 'numeric',
            'AlturaExtension'   => 'numeric',
            'CubicacionReal'    => 'required|numeric',
            'CubicacionParaPago'    => 'required|numeric',
            //Imagenes
            'Frente'        => 'mimes:jpeg,bmp,png,jpg,gif',
            'Dereche'       => 'mimes:jpeg,bmp,png,jpg,gif',
            'Atras'         => 'mimes:jpeg,bmp,png,jpg,gif',
            'Izquierda'     => 'mimes:jpeg,bmp,png,jpg,gif'
        ];
    }
    
    public function messages() {
        return [
            'IdSindicato.exists'    => 'No existe un :attribute con el Id: '. $this->IdSindicato,
            'IdOperador.exists'     => 'No existe un :attribute con el Id: '. $this->IdOperador,
            'Economico.unique'      => 'Ya existe un camión con el siguiente Número Económico: ' . $this->Economico,
            'Placas.unique'         => 'Ya existe un camión con las siguientes Placas: ' . $this->Placas,
            'IdMarca.exists'        => 'No existe una :attribute con el Id: '. $this->IdMarca,
            'IdBoton.exists'        => 'No existe un :attribute con el Id: '. $this->IdBoton
        ];
    }
}
