<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseFormRequest;

class CartCreateRequest extends BaseFormRequest
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
            'route_id' => ['required', 'exists:routes,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'products_array' => ['required'],
        ]);

        return [
            'route_id',
            'client_id',
        ];
    }
}
