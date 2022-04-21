<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $rules = [
            'first_name' => 'required|max:250',
            'last_name' => 'required|max:250',
            'email' => [
                'email',
                'required',
                'max:250',
                Rule::unique('users')->ignore($this->id),
            ],
            'phone' => [
                'required',
                'max:11',
                'min:11',
                Rule::unique('users')->ignore($this->id),
            ],
            'password' => 'required|max:250',
            'avatar' => 'image|mimes:jpg,png,jpeg,gif,svg,webp'
        ];

        if (request()->method() == 'PATCH') {
            $rules['password'] = 'max:250';
        }

        return $rules;
    }
}
