<?php

namespace App\Http\Requests\Transfer;

use App\Http\Requests\BaseFormRequest;

class TransferRequest extends BaseFormRequest
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
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

       return [
            'client_id',
            'amount',
        ];
    }
}
