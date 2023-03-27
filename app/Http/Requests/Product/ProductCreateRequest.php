<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseFormRequest;

class ProductCreateRequest extends BaseFormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'stock' => ['integer', 'min:0'],
            'price' => ['required', 'numeric', 'min:0'],
        ]);

       return [
            'name',
            'email',
            'password',
        ];
    }
}
