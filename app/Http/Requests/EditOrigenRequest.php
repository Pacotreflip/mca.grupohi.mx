<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditOrigenRequest extends Request
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
            'Descripcion' => 'required|unique:sca.origenes,Descripcion,'.$this->route('origenes').',IdOrigen',
            'Estatus' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'IdTipoOrigen.required' => 'El campo Tipo es obligatorio.',
            'IdTipoOrigen.numeric'  => 'EL campo :attribute debe ser numérico.',
            'IdTipoOrigen.exists'   => 'No existe un Tipo con el Id: '. $this->IdTipoOrigen,
            'Descripcion.required'  => 'El campo :attribute es obligatorio.',
            'Descripcion.unique'    => 'Ya existe un origen con la siguiente descripción: ' . $this->Descripcion,
            'Estatus.required'      => 'El campo :attribute es obligatorio.',
            'Estatus.numeric'       => 'EL campo :attribute debe ser numérico.',
        ];

        return $messages;
    }
}
