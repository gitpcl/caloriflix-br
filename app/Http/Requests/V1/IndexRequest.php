<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndexRequest extends FormRequest
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
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string|max:255',
            'sort_by' => 'nullable|string|in:' . implode(',', $this->allowedSortFields()),
            'sort_direction' => 'nullable|string|in:asc,desc',
            'start_date' => 'nullable|date|date_format:Y-m-d',
            'end_date' => 'nullable|date|date_format:Y-m-d|after_or_equal:start_date',
        ];
    }

    /**
     * Get allowed sort fields for the current request.
     *
     * @return array<string>
     */
    protected function allowedSortFields(): array
    {
        // Override this method in child classes to specify allowed sort fields
        return ['created_at', 'updated_at'];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'per_page.max' => 'You can request a maximum of 100 items per page.',
            'sort_direction.in' => 'Sort direction must be either "asc" or "desc".',
            'start_date.date_format' => 'Start date must be in format YYYY-MM-DD.',
            'end_date.date_format' => 'End date must be in format YYYY-MM-DD.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation Error.',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}