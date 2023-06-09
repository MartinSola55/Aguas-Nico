<?php

namespace App\Http\Requests\Route;

use App\Http\Requests\BaseFormRequest;;

class RouteCreateRequest extends BaseFormRequest
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
            'day_of_week' => ['required', 'int'],
        ]);

        return [
            'user_id',
            'day_of_week',
        ];
    }
}
