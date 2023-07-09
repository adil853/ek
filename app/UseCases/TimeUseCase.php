<?php

namespace App\UseCases;

use App\Infrastructure\Persistence\TimeRepository;
use App\Models\Time;
use App\Models\User;
use App\Domain\Services\TimeValidator;
use App\Services\TimeService;

class TimeUseCase
{
    private TimeValidator $validator;
    private TimeRepository $timeRepository;

    private TimeService $timeService;

    public function __construct(TimeValidator $validator, TimeRepository $timeRepository, TimeService $timeService)
    {
        $this->validator = $validator;
        $this->timeRepository = $timeRepository;
        $this->timeService = $timeService;
    }

    public function execute($input): \Illuminate\Http\JsonResponse
    {
        $errors = $this->validator->validateTime($input);
        if ($errors != null) {
            return response()->json(['errors' => $errors], 420);
        }


        $res = $this->timeService->breakTime($input);
        if ($res["error"]) {
            return $this->sampleRequest($res);
        }
        $time = new Time(["start_time" => $input["start_time"], "end_time" => $input["end_time"], "time_expressions" => $res["body"]]);
        $this->timeRepository->save($time);
        return $this->response($res);
    }


    private function sampleRequest(array $res): \Illuminate\Http\JsonResponse
    {
        return response()->json(['errors' => $res["message"], 'sampleRequest' => ['start_time' => '2020-01-01 00:00:00', 'end_time' => '2020-03-15 00:00:10', 'time_expressions' => ["2m", "1d", "2h", "3s"]]], 420);
    }

    private function response(array $res): \Illuminate\Http\JsonResponse
    {
        return response()->json(["message" => "Key in following json is unit name and value is its weightage", "data" => $res["body"]], 200);
    }

}


