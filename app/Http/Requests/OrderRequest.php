<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'name' => 'required|string',
    		'surname' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'required|string',
//            'city' => 'required|string',
            //'street' => 'required|string',
            'ip_account' => 'required',
            'cart_items' => 'required',
            'delivery' => 'required',
            'payment' => 'required',
            'privacy' => 'required',
            'data' => 'array',
            'data.org_bin' => 'required_if:ip_account,==,1',
            'data.org_name' => 'required_if:ip_account,==,1',
            'data.org_address' => 'required_if:ip_account,==,1',
            'data.delivery_price' => 'required|integer',
            'data.total' => 'required|integer',
        ];
    }
}
