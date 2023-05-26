<?php

namespace App\Http\Requests\exhibitor;

use Illuminate\Foundation\Http\FormRequest;

class ExhibtorStoreRequest extends FormRequest
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
            'logo' => 'image',
            'name' => 'required|max:191',
            'email' => 'required|max:191|email',
            'category' => 'required|max:191',
            'address' => 'required|max:191',
            'instagram' => 'required|max:191|active_url|url',
            'linkedin' => 'required|max:191|active_url|url',
            'twitter' => 'required|max:191|active_url|url',
            'website' => 'required|max:191|active_url|url',
            'video' => 'required|max:191|active_url|url',
            'booth_link' => 'required|max:191|active_url|url',
            'booth_image' => 'image',
            'description' => 'required',
            'phone' => 'numeric|digits_between:10,13',
        ];
    }

    public function messages()
    {
        return [
            'name.max' => 'karakter field nama tidak boleh melebihi 191 karakter',
            'name.required' => 'tolong isi field nama',

            'website.max' => 'karakter field website tidak boleh melebihi 191 karakter',
            'website.active_url' => 'inputan yang anda masukkan bukan URL yang aktif',
            'website.url' => 'inputan yang anda masukkan bukan URL yang aktif',

            'logo.required' => 'tolong isi field logo',
            'booth_image.required' => 'tolong isi field booth image',

            'address.requried' => 'tolong isi field alamat',
            'address.max' => 'karakter field alamat tidak boleh melebihi 191 karakter',
        ];
    }
}
