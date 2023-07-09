<?php

namespace App\Domain\Services;

use Illuminate\Http\Request;
use function App\Http\Controllers\validateInput;

class TimeValidator
{
    public function validateTime($inputs): bool|\Illuminate\Support\MessageBag|null
    {
        $validator = $this->validateInputNew($inputs);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return null;
    }

    public function validateSearchTime($inputs): bool|\Illuminate\Support\MessageBag|null
    {
        $validator = $this->validateSearchTimeInput($inputs);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return null;
    }

    function validateInputNew(array $data): \Illuminate\Validation\Validator
    {
        return \Illuminate\Support\Facades\Validator::make($data, [
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
            'time_expressions.*' => ['distinct'],
            'time_expressions' => [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $item) {
                        if (!preg_match('/^(\d+)?[mdhis]$/', $item)) {
                            $fail("The $attribute array can only contain strings with the formats 'm', 'd', 'h', 'i', 's' or positive integers followed by 'm', 'd', 'h', 'i', 's'.");
                            break;
                        }
                    }
                },
            ],
        ]);
    }

    function validateSearchTimeInput(array $data): \Illuminate\Validation\Validator
    {
        return \Illuminate\Support\Facades\Validator::make($data, [
            'start_time' => 'required|date_format:Y-m-d H:i:s',
            'end_time' => 'required|date_format:Y-m-d H:i:s',
        ]);
    }
}
