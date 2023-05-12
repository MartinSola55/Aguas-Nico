<?php

namespace App\Http\Requests\Route;

use App\Http\Requests\BaseFormRequest;

class ProductDispatchedUpdateRequest extends BaseFormRequest
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
            'products_quantity' => ['required'],
            'products_quantity.*.product_id' => ['required', 'exists:products,id'],
            'products_quantity.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return [
            'product_id',
            'route_id',
            'quantity',
        ];
    }
}
