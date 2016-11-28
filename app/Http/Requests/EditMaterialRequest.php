<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class EditMaterialRequest extends Request
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
            'Estatus' => 'required|numeric',
            'Descripcion' => 'required|unique:sca.materiales,Descripcion,'.$this->route('materiales').',IdMaterial'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'Descripcion.unique'    => 'Ya existe un material con la siguiente descripción: ' . $this->Descripcion,
        ];

        return $messages;
    }
}
