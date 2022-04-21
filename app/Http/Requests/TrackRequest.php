<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackRequest extends FormRequest
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
            'file' => 'required',
            'singer_id' => 'required|exists:singers,id',
            'album_id' => 'nullable|exists:albums,id',
            'genre_id' => 'nullable|exists:genres,id',
            'is_vip' => 'bool',
            'published_date' => 'nullable|date_format:Y-m-d',
            'cover' => 'image|mimes:jpg,png,jpeg,gif,svg,webp'
        ];
    }
}
