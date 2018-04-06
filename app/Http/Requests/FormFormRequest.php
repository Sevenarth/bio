<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormFormRequest extends FormRequest
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
            'counter' => 'required|numeric|min:0',
            'countries.*' => ['required', Rule::in(array_map(function($country) { return $country['domain']; }, config('app.amz_countries')))],
            'description' => 'required',
            'images.*' => 'nullable|url',
            'category' => 'nullable|exists:categories,id',
            'counts_on_time' => 'required|numeric|min:1',
            'counts_on_space' => ['required', Rule::in(array_keys(config('app.timeSpaces')))],
            'starts_on_date' => 'nullable|date',
            'starts_on_time' => ['nullable','regex:/^([0-1][0-9]|2[0-3])\:[0-5][0-9]$/']
        ];
    }

    public function messages()
    {
      return [
        '*.required' => __('This field is compulsory.'),
        'title.string' => __("The :name has to be a string.", ['name' => __('title field')]),
        'counter.numeric' => __("The :name has to be a number.", ['name' => __('remaining quantity field')]),
        'counter.min' => __("The :name has to be at least :min.", ['name' => __('remaining quantity field')]),
        'images.*.url' => __("This picture must have a valid URL"),
        'category.exists' => __("The selected category does not exist."),
        'counts_on_time.numeric' => __('This field must have a valid number'),
        'counts_on_time.min' => __('This field must have a valid number'),
        'counts_on_space.in' => __('Invalid space type.'),
        '*.date' => __('Choose a valid date.')
      ];
    }
}
