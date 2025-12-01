<?php

namespace App\Http\Requests\Service;

use Illuminate\Foundation\Http\FormRequest;

class StoreWorkingHourRequest extends FormRequest
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
            'days' => ['required', 'array', 'min:1'],
            'days.*' => ['required', 'integer', 'between:0,6', 'distinct'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'days.required' => 'Days are required.',
            'days.array' => 'Days must be an array.',
            'days.min' => 'At least one day must be selected.',
            'days.*.required' => 'Each day value is required.',
            'days.*.integer' => 'Each day must be an integer.',
            'days.*.between' => 'Each day must be between Sunday and Saturday .',
            'days.*.distinct' => 'Duplicate days are not allowed.',
            'start_time.required' => 'Start time is required.',
            'start_time.date_format' => 'Start time must be in H:i format.',
            'end_time.required' => 'End time is required.',
            'end_time.date_format' => 'End time must be in H:i format.',
            'end_time.after' => 'End time must be after start time.',
        ];
    }
}
