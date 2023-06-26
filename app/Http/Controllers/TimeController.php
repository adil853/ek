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
            'time_expressions' => $request->input('time_expressions')
        ];

        $validator = validateInput($inputs);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['errors' => $errors], 420);
        }


        $timeService = new \App\Services\TimeService();
        $res = $timeService->breakTime($inputs);
        if ($res["error"]) {
            return response()->json(['errors' => $res["message"], 'sampleRequest' => ['start_time'=>'2020-01-01 00:00:00', 'end_time'=>'2020-03-15 00:00:10', 'time_expressions'=>["2m","1d", "2h", "3s"]]], 420);
        }

        return response()->json(["message"=>"Key in following json is unit name and value is its weightage","data" => $res["body"]],200);
    }

    public function searchBreakTime(Request $request)
    {

        $inputs = [
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ];


        $validator = validateSearchInput($inputs);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json(['errors' => $errors], 420);
        }

        $timeService = new \App\Services\TimeService();
        return $timeService->getBreakTime($request->input('start_time'), $request->input('end_time'));

    }
}


function validateSearchInput(array $data)
{
    return \Illuminate\Support\Facades\Validator::make($data, [
        'start_time' => 'required|date_format:Y-m-d H:i:s',
        'end_time' => 'required|date_format:Y-m-d H:i:s',
    ]);
}
function validateInput(array $data)
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

