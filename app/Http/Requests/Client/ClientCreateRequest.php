<?php

namespace App\Http\Requests\Client;

use App\Http\Requests\BaseFormRequest;

class ClientCreateRequest extends BaseFormRequest
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
            'adress' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'debt' => ['required', 'numeric'],
            'dni' => ['nullable', 'string', 'max:255'],
            'invoice' => ['boolean'],
            'observation' => ['nullable', 'string'],
        ]);

       return [
            'name',
            'adress',
            'phone',
            'email',
            'debt',
            'dni',
            'invoice',
            'is_active',
            'observation',
            'user_id',
        ];
    }
}
