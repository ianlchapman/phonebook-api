<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        $contact = $this->route('contact');
        return $this->user()->can('update', $contact);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'firstName' => ['sometimes', 'string'],
            'lastName' => ['sometimes', 'string'],
            'email' => ['sometimes', 'nullable', 'email'],
            'phoneHome' => ['sometimes', 'nullable', 'string'],
            'phoneMobile' => ['sometimes', 'nullable', 'string']
        ];
    }
}
