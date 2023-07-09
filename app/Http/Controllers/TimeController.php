<?php

namespace App\Http\Controllers;

use App\UseCases;
use Illuminate\Http\Request;

class TimeController extends Controller
{

    private UseCases\TimeUseCase $timeUseCase;
    private UseCases\SearchTimeUseCase $searchBreakTime;

    public function __construct(UseCases\TimeUseCase $timeUseCase, UseCases\SearchTimeUseCase $searchBreakTime)
    {
        $this->timeUseCase = $timeUseCase;
        $this->searchBreakTime = $searchBreakTime;
    }

    public function breakTime(Request $request): \Illuminate\Http\JsonResponse
    {
        $inputs = [
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'time_expressions' => $request->input('time_expressions')
        ];
        return $this->timeUseCase->execute($inputs);
    }

    public function searchBreakTime(Request $request)
    {
        $inputs = [
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ];
        return $this->searchBreakTime->execute($inputs);
    }
}

