<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateDestinoRequest extends Request
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
            'Descripcion' => 'required|unique:sca.tiros,Descripcion',
            'Estatus' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'Descripcion.required'  => 'El campo :attribute es obligatorio.',
            'Descripcion.unique'    => 'Ya existe un destino con la siguiente descripción: ' . $this->Descripcion,
            'Estatus.required'      => 'El campo :attribute es obligatorio.',
            'Estatus.numeric'       => 'EL campo :attribute debe ser numérico.',
        ];

        return $messages;
    }
}