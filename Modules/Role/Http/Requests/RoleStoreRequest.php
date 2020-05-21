<?php

namespace Modules\Role\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|unique:roles,name',
        ];
    }

    public function messages()
    {
        return [
            'name.unique' => 'El rol ingresado ya se encuentra registrado',
            'name.required' => 'Ingrese el nombre del rol',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->isAdmin() || auth()->user()->can('add-role')){
            return true;
        }
        return false;
    }
}
