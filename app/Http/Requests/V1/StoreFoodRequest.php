<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFoodRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50|in:g,kg,mg,ml,l,oz,lb,cup,tbsp,tsp,piece,slice,serving',
            'protein' => 'required|numeric|min:0',
            'fat' => 'required|numeric|min:0',
            'carbohydrate' => 'required|numeric|min:0',
            'fiber' => 'nullable|numeric|min:0',
            'calories' => 'required|numeric|min:0',
            'barcode' => 'nullable|string|max:100',
            'is_favorite' => 'nullable|boolean',
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
            'name.required' => 'The food name is required.',
            'quantity.required' => 'The quantity is required.',
            'unit.required' => 'The unit of measurement is required.',
            'unit.in' => 'The unit must be one of: g, kg, mg, ml, l, oz, lb, cup, tbsp, tsp, piece, slice, serving.',
            'protein.required' => 'The protein amount is required.',
            'fat.required' => 'The fat amount is required.',
            'carbohydrate.required' => 'The carbohydrate amount is required.',
            'calories.required' => 'The calorie amount is required.',
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