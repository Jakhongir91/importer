<?php

namespace App\Domain\Import\Request;

use Illuminate\Foundation\Http\FormRequest;

class Import extends FormRequest
{
    public function rules()
    {
        return [
            "file" => [
                "file",
                "required",
            ]
        ];
    }
}
