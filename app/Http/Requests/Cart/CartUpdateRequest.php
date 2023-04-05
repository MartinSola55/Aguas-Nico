<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseFormRequest;

class CartUpdateRequest extends BaseFormRequest
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
            'delivered' => ['required', 'boolean'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);

        return [
            'route_id',
            'client_id',
            'delivered',
            'start_date',
            'end_date',
        ];
    }
}
