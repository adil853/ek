<?php

namespace App\UseCases;

use App\Domain\Services\TimeValidator;
use App\Infrastructure\Persistence\TimeRepository;

class SearchTimeUseCase
{
    private TimeValidator $validator;
    private TimeRepository $timeRepository;

    public function __construct(TimeValidator $validator, TimeRepository $timeRepository,)
    {
        $this->validator = $validator;
        $this->timeRepository = $timeRepository;

    }

    public function execute($input): \Illuminate\Http\JsonResponse
    {
        $errors = $this->validator->validateSearchTime($input);
        if ($errors != null) {
            return response()->json(['errors' => $errors], 420);
        }


        $criteria = [
            'start_time' => $input["start_time"],
            'end_time' => $input["end_time"],
        ];


        $response = $this->timeRepository->findWhere($criteria);
        return response()->json(["data" => $response], 200);
    }


}
