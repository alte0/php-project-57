<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTasksRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255',],
            'description' => ['string', 'nullable', 'max:255',],
            'status_id' => ['required', 'exists:task_statuses,id',],
            'assigned_to_id' => ['integer', 'exists:users,id', 'nullable',],
        ];
    }
}
