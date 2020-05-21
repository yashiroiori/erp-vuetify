<?php

namespace Modules\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ingrese el nombre del usuario',
            'email.required' => 'Ingrese el correo electrónico',
            'email.email' => 'Ingrese un correo electrónico válido',
            'email.unique' => 'El correo electrónico ingresado ya se encuentra registrado',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if(auth()->user()->isAdmin() || auth()->user()->can('add-user')){
            return true;
        }
        return false;
    }
}
