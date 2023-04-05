<?php

namespace App\Http\Requests\Route;

use App\Http\Requests\BaseFormRequest;

class RouteUpdateRequest extends BaseFormRequest
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
            'start_daytime' => ['required', 'date'],
            'end_daytime' => ['required', 'date', 'after:start_daytime'],
        ]);

        return [
            'user_id',
            'start_daytime',
            'end_daytime',
        ];
    }
}
