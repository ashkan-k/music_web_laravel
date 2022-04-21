<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WishListRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'wish_listable_id' => 'required',
            'wish_listable_type' => 'required',
        ];
    }
}
