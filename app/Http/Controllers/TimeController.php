<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use TimeService;

class TimeController extends Controller
{
    public function breakTime(Request $request)
    {
        $inputs = [
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'strings' => $request->input('time_expressions')
        ];

        $validator = validateInput($inputs);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['errors' => $errors], 420);
        }

        $timeService = new \App\Services\TimeService();
        $res = $timeService->breakTime();

        return response()->json(200);
    }
}


function validateInput(array $data)
{
    return \Illuminate\Support\Facades\Validator::make($data, [
        'start_time' => 'required|date_format:Y-m-d H:i:s',
        'end_time' => 'required|date_format:Y-m-d H:i:s',
        'strings' => [
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
