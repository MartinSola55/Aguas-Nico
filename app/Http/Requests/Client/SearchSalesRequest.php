<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseFormRequest;

class SearchSalesRequest extends BaseFormRequest
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
            'client_id' => ['required', 'exists:clients,id'],
            'dateFrom' => ['required', 'date'],
            'dateTo' => ['required', 'date', 'after:dateFrom'],
        ]);

        return [
            'clientId',
            'dateFrom',
            'dateTo',
        ];
    }
}
