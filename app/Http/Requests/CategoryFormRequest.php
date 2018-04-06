<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryFormRequest extends FormRequest
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
            'title' => 'required|string|max:191',
            'parent_id' => 'nullable|present'
        ];
    }

    public function messages()
    {
      return [
        'title.required' => __("This field is compulsory."),
        'title.string' => __("The :name has to be a string.", ['name' => __('name field')])
      ];
    }
}
