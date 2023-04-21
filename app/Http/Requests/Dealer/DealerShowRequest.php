<?php

namespace App\Http\Requests\Dealer;

use App\Http\Requests\BaseFormRequest;

class DealerShowRequest extends BaseFormRequest
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
            'id' => ['required', 'exists:users,id'],
        ]);

       return [
        'name',
        'adress',
        'phone',
        'email',
        'debt',
        'dni',
        'invoice',
        'observation',
        ];
    }
}
