<?php

namespace App\Http\Requests\admin\faq;

use Illuminate\Foundation\Http\FormRequest;

class FaqStoreRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'pertanyaan'    => 'required|max:255',
            'jawaban'       => 'required',
        ];
    }

    public function messages()
    {
        return [
            'pertanyaan.max'        => 'Maksimal Untuk Pertanyaan adalah 255 Karakter',
            'pertanyaan.required'   => 'Tolong isi Pertanyaan ',
            'jawaban.required'      => 'Tolong isi Jawaban untuk Pertanyaan ini'
        ];
    }
}
