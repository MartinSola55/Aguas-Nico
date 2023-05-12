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
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'route_id' => 'required|exists:routes,id',
        ]);

        return [
            'product_id',
            'route_id',
            'quantity',
        ];
    }
}
