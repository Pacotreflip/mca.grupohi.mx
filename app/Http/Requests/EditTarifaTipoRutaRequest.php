<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditTarifaTipoRutaRequest extends Request
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
            'IdTipoRuta' => 'required|numeric|exists:sca.tipo_ruta,IdTipoRuta',
            'PrimerKM' => 'required|numeric|min:0',
            'KMSubsecuente' => 'required|numeric|min:0',
            'KMAdicional' => 'required|numeric|min:0'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'IdTipoRuta.exists'   => 'No existe un Tipo de Ruta con el Id: '. $this->IdTipoRuta,
        ];

        return $messages;
    }
}