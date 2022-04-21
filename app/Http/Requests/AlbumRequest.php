<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AlbumRequest extends FormRequest
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
            'name' => 'required|max:250',
            'singer_id' => 'required|exists:singers,id',
            'published_date' => 'date',
            'is_vip' => 'bool',
            'cover' => 'image|mimes:jpg,png,jpeg,gif,svg,webp'
        ];
    }
}
