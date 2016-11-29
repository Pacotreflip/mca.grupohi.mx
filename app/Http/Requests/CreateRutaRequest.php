<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateRutaRequest extends Request
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
            'IdOrigen'          => 'required|exists:sca.origenes,IdOrigen',
            'IdTiro'            => 'required|exists:sca.tiros,IdTiro',
            'IdTipoRuta'        => 'required|exists:sca.tipo_ruta,IdTipoRuta',
            'PrimerKm'          => 'required|numeric|max:1',
            'KmSubsecuentes'    => 'numeric|min:0',
            'KmAdicionales'     => 'numeric|min:0',
            'TotalKM'           => 'required|numeric',
            'TiempoMinimo'      => 'required|numeric|min:0',
            'Tolerancia'        => 'required|numeric|min:0',
            'Croquis'           => 'mimes:jpeg,bmp,png,jpg,pdf,gif'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'IdOrigen.exists'   => 'No existe un :attribute con el Id: '. $this->IdOrigen,
            'IdTiro.exists'   => 'No existe un :attribute con el Id: '. $this->IdTiro,
            'IdTipoRuta.exists'   => 'No existe un :attribute con el Id: '. $this->IdTipoRuta
        ];

        return $messages;
    }
}