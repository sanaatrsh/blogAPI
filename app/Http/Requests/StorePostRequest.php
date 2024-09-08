<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StorePostRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        $this->merge([
            'author_id' => Auth::user()->id,
        ]);
    }
    public function rules(): array
    {
        return [
            "title" => 'required|string|max:255',
            "image" => 'nullable|image',
            "body" => 'required',
            "author_id" => 'exists:users,id|required'
        ];
    }
}
