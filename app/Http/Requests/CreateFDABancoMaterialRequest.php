<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;

class CreateFDABancoMaterialRequest extends Request
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
            'IdBanco'    => 'required|numeric|exists:sca.origenes,IdOrigen',
            'FactorAbundamiento' => 'required|numeric'
        ];
    }
    
    public function messages() {
        return [
            'IdMaterial.exists' => 'No existe un :attribute con el Id: '. $this->IdMaterial,
            'IdBanco.exists'  => 'No existe un :attribute con el Id: '. $this->IdBanco
        ];
    }
}
