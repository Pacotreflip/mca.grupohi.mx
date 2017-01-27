<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateOrigenRequest extends Request
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
            'IdTipoOrigen' => 'required|numeric|exists:sca.tiposorigenes,IdTipoOrigen',
            'Descripcion' => 'required|unique:sca.origenes,Descripcion',
        ];
    }
    
    public function messages()
    {
        $messages = [
            'IdTipoOrigen.exists'   => 'No existe un Tipo de Origen con el Id: '. $this->IdTipoOrigen,
            'Descripcion.unique'    => 'Ya existe un origen con la siguiente descripción: ' . $this->Descripcion,
        ];

        return $messages;
    }
}
