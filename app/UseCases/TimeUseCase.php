<?php

namespace App\UseCases;

use App\Infrastructure\Persistence\TimeRepository;
use App\Models\Time;
use App\Models\User;
use App\Domain\Services\TimeValidator;

class TimeUseCase
{
    private TimeValidator $validator;
    private TimeRepository $timeRepository;

    public function __construct(TimeValidator $validator, TimeRepository $timeRepository)
    {
        $this->validator = $validator;
        $this->timeRepository = $timeRepository;
    }

    public function execute($input): \Illuminate\Http\JsonResponse
    {
        $errors = $this->validator->validateTime($input);
        if ($errors != null) {
            return response()->json(['errors' => $errors], 420);
        }

        $r = '{
            "2m": 0,
            "1d": "2.00",
            "2h": "5.00",
            "1s": "661.00"
        }';
        $time = new Time(["start_time" => "2020-03-14 00:00:00", "end_time" => "2020-03-16 00:11:01", "time_expressions" => $r]);
        $this->timeRepository->save($time);
        return response()->json(['message' => 'User created successfully'], 200);
    }
}
