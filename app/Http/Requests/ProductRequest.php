<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:250',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
            'description' => 'required|max:250',
            'providerId' => 'required|exists:providers,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'image2' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'image3' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            'stock' => 'required|numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
       throw new HttpResponseException(response()->json([
        'success'=> false,
        'message'=> 'Error de validación',
        'errors'=> $validator->errors()
        ], 422));
    }
}
