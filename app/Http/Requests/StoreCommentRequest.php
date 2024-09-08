<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
        $post_id = $this->route('post_id');
        $this->merge([
            'post_id' =>  $post_id,
        ]);
        // dd($post_id);
    }
    public function rules(): array
    {
        return [
            "user_id" => 'required|exists:users,id',
            "post_id" => 'exists:posts,id|required',
            "comment" => 'required|string|max:500',
        ];
    }
}
