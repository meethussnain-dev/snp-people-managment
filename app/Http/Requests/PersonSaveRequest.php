<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonSaveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return self::rulesFor($this->route('person'));
    }

    public static function rulesFor(?int $personId = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'sa_id_number' => [
                'required',
                'string',
                'min:13',
                'max:255',
                Rule::unique('people', 'sa_id_number')->ignore($personId),
            ],
            'mobile_number' => ['required', 'string', 'min:10', 'max:20'],
            'email' => ['required', 'email', 'max:255', Rule::unique('people', 'email')->ignore($personId)],
            'birth_date' => ['required', 'date', 'before:today'],
            'language_id' => ['required', 'exists:languages,id'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['exists:interests,id'],
        ];
    }
}
