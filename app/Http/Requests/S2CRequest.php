<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class S2CRequest extends FormRequest
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
            'VendorID' => ['required', 'string', 'max:255'],
            'ASPID'    => ['required', 'string', 'max:255'],
            'country'  => ['required', 'string', 'max:100'],
            'ccode'    => ['required', 'string', 'max:20'],
            'link'     => ['required', 'in:test,live'],

        ];
    }

    public function messages(): array
    {
        return [
            'VendorID.required' => 'Vendor ID is required.',
            'ASPID.required'    => 'ASPID is required.',
            'country.required'  => 'Country is required.',
            'ccode.required'    => 'Country code is required.',
            'link.required'     => 'Link is required.',
            'link.in' => 'Link must be either test or live.',

        ];
    }
}
