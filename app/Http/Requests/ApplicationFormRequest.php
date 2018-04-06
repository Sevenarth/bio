<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Request;
use Validator;
use App\Tester;
use Hashids;

class ApplicationFormRequest extends FormRequest
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

        Validator::extend('recaptcha', function ($message, $attribute, $rule, $parameters) {
            $recaptcha = new \ReCaptcha\ReCaptcha(config('app.recaptcha_secret_key'));
            $resp = $recaptcha->verify($attribute, Request::ip());
  
            if ($resp->isSuccess())
              return true;
            else
              return false;
          });

        return [
            'first_name' => 'required|string|max:30',
            'last_name' => 'required|string|max:20',
            'email' => 'required|email|max:30',
            'profile_links' => 'min:1',
            'profile_countries' => 'min:1',
            'profile_links.*' => 'required_with:profile_countries.*',
            'profile_countries.*' => ['required_with:profile_links.*', Rule::in(array_map(function($country) { return $country['domain']; }, config('app.amz_countries')))],
            'g-recaptcha-response' => 'required|recaptcha'
        ];
    }

    public function messages()
    {
        return [
            '*.required' => __("This field is compulsory."),
            '*.max' => __('This field may contain maximum :max letters.'),
            'first_name.string' => __("The :name has to be a string.", ['name' => __('first name field')]),
            'last_name.string' => __("The :name has to be a string.", ['name' => __('last name field')]),
            'email.email' => __('The :name has to be a valid email address.', ['name' => __('email address field')]),
            'profile_links.*.required_with' => __('You need to enter a valid Amazon:domain profile URL!', ['domain' => '']),
            'profile_countries.*.required_with' => __('You need to choose a valid country!'),
            'profile_countries.*.in' => __('You need to choose a valid country!')
        ];
    }

    public function withValidator($validator) {
        $validator->after(function ($validator) {
            foreach ($this->input('profile_countries') as $i => $country)
                if(!empty($country) && !empty($this->input('profile_links.'.$i))) {
                    $url = parse_url($this->input('profile_links.'.$i), PHP_URL_HOST);
                    if($url === false || stripos($url, "amazon.".$country) === false)
                        $validator->errors()->add('profile_links.'.$i, __('You need to enter a valid Amazon:domain profile URL!', ['domain' => '.'.$country]));
                }

            try {
                $id = Hashids::decode($this->hash);
            } catch(Exception $e) {
                abort(404);
            }
    
            if(count($id) < 1 || empty($id[0]) || !is_numeric($id[0]))
                abort(404);
    
            $tester = Tester::whereEmail($this->input('email'))->first();
            if($tester && $tester->forms->contains($id[0]))
                $validator->errors()->add('email', __('You have already applied to this post!'));

        });
    }
}
