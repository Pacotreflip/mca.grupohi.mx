<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateTarifaMaterialRequest extends Request
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
            'IdMaterial' => 'required|numeric|exists:sca.materiales,IdMaterial',
            'PrimerKM' => 'required|numeric|min:0',
            'KMSubsecuente' => 'required|numeric|min:0',
            'KMAdicional' => 'required|numeric|min:0'
        ];
    }
    
    public function messages()
    {
        $messages = [
            'IdMaterial.exists'   => 'No existe un Material con el Id: '. $this->IdMaterial,
        ];

        return $messages;
    }
}
