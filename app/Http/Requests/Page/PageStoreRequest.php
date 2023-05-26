<?php

namespace App\Http\Requests\Page;

use Illuminate\Foundation\Http\FormRequest;

class PageStoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'txtTitle' => 'required|max:255',
            'konten' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'txtTitle.max' => 'Maksimal Untuk Judul Page adalah 255 Karakter',
            'txtTitle.required' => 'Tolong isi Judul Untuk Page',
            'konten.required' => 'Tolong isi Konten Untuk Page'
        ];
    }
}
