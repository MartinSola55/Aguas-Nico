<?php

namespace App\Http\Requests\Cart;

use App\Http\Requests\BaseFormRequest;

class ConfirmRequest extends BaseFormRequest
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
            'cart_id' => ['required', 'exists:carts,id'],
            'products_quantity' => ['required'],
            'products_quantity.*.product_id' => ['required', 'exists:products,id'],
            'products_quantity.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_methods' => ['required'],
            'payment_methods.*.method' => ['required', 'string', 'max:255'],
            'payment_methods.*.amount' => ['required', 'numeric', 'min:0'],
        ]);

        return [
            'cart_id',
            'products_quantity',
            'payment_methods',
        ];
    }
}
