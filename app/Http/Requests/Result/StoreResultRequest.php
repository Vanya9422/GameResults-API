<?php

namespace App\Http\Requests\Result;

use App\Traits\HandlesApiValidation;
use Illuminate\Foundation\Http\FormRequest;

class StoreResultRequest extends FormRequest {

    use HandlesApiValidation;

    public function rules(): array {

        return [
            'milliseconds' => 'required|integer',
            'email' => 'sometimes|required|email|unique:members,email',
        ];
    }
}
