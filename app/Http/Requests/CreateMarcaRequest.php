<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateMarcaRequest extends Request
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
            'Descripcion' => 'required|unique:sca.marcas,Descripcion'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'Descripcion.unique'    => 'Ya existe una marca con la siguiente descripción: ' . $this->Descripcion,
        ];

        return $messages;
    }
}
