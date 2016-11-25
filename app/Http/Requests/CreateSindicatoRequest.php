<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateSindicatoRequest extends Request
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
            'Descripcion' => 'required|unique:sca.sindicatos,Descripcion',
            'NombreCorto' => 'required|unique:sca.sindicatos,NombreCorto',
            'Estatus' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'Descripcion.required'  => 'El campo :attribute es obligatorio.',
            'Descripcion.unique'    => 'Ya existe un sindicato con la siguiente descripción: ' . $this->Descripcion,
            'NombreCorto.required'  => 'El campo :attribute es obligatorio.',
            'NombreCorto.unique'    => 'Ya existe un sindicato con el siguiente nombre corto: ' . $this->NombreCorto,
            'Estatus.required'      => 'El campo :attribute es obligatorio.',
            'Estatus.numeric'       => 'EL campo :attribute debe ser numérico.'
        ];

        return $messages;
    }
}
