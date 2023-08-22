<?php

namespace App\Http\Api\Requests;

use App\Services\IntervalService;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class IntervalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'success' => 'required|boolean',
            'data' => 'required|array',
            'data.seance_date' => 'sometimes|required|integer',
            'data.seances.*' => 'sometimes|array',
            'data.seances.*.time' => [
                'required',
                'string',
                'date_format:H:i',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (strtotime($value) < strtotime(IntervalService::$jobStart) ||
                        strtotime($value) > strtotime(IntervalService::$jobEnd)) {
                        $fail("The {$attribute} is invalid.");
                    }
                }
            ],
            'data.seances.*.seance_length' => 'required|integer|min:1800|max:7200',
        ];
    }
}
