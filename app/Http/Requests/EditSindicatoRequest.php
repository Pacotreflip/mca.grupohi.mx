<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditSindicatoRequest extends Request
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
            'Descripcion' => 'required|unique:sca.sindicatos,Descripcion,'.$this->route('sindicatos').',IdSindicato',
            'NombreCorto' => 'required|unique:sca.sindicatos,NombreCorto,'.$this->route('sindicatos').',IdSIndicato',
            'Estatus' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'Descripcion.unique'    => 'Ya existe un sindicato con la siguiente descripción: ' . $this->Descripcion,
            'Descripcion.required'  => 'El campo :attribute es obligatorio.',
            'NombreCorto.required'  => 'El campo :attribute es obligatorio.',
            'NombreCorto.unique'    => 'Ya existe un sindicato con el siguiente nombre corto: ' . $this->NombreCorto,
            'Estatus.numeric'       => 'EL campo :attribute debe ser numérico.',
            'Estatus.required'      => 'El campo :attribute es obligatorio.',
        ];

        return $messages;
    }
}
