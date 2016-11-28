<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditTiroRequest extends Request
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
            'Descripcion' => 'required|unique:sca.tiros,Descripcion,'.$this->route('tiros').',IdTiro',
            'Estatus' => 'required|numeric'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'Descripcion.unique'    => 'Ya existe un tiro con la siguiente descripción: ' . $this->Descripcion,
        ];

        return $messages;
    }
}