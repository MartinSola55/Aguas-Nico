<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseFormRequest;

class ClientUpdateInvoiceRequest extends BaseFormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $this->validate([
            'invoice_type' => ['required', 'string', 'max:255'],
            // 'business_name' => ['required', 'string', 'max:255'],
            'tax_condition' => ['required', 'string', 'max:255'],
            'cuit' => ['required', 'string', 'max:255'],
            // 'tax_address' => ['required', 'string', 'max:255'],
        ]);

       return [
            'user_id',
            'invoice_type',
            // 'business_name',
            'tax_condition',
            'cuit',
            // 'tax_address'
        ];
    }
}
