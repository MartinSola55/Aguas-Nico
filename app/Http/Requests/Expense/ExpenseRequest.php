<?php

namespace App\Http\Requests\Expense;

use App\Http\Requests\BaseFormRequest;

class ExpenseRequest extends BaseFormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'spent' => ['required', 'numeric', 'min:0'],
            'description' => ['required', 'string'],
        ]);

        return [
            'user_id',
            'spent',
            'description',
        ];
    }
}
