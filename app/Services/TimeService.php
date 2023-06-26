<?php

namespace App\Services;

class TimeService
{


    public function breakTime(array $data): string
    {
        $startTime = $data["start_time"];
        $endTime = $data["end_time"];
        $timeExpressions = $data["time_expressions"];

        print_r(separateTimeStrings($timeExpressions));


        return "breakTime from service is called";
    }

}


function separateTimeStrings(array $timeStrings): array
{
    $result = [
        'm' => [],
        'd' => [],
        'h' => [],
        'i' => [],
        's' => [],
    ];

    foreach ($timeStrings as $timeString) {
        $unit = preg_replace('/\d+/', '', $timeString); // Extract the unit (m, d, h, i, s)
        $value = intval(preg_replace('/[^\d]/', '', $timeString)); // Extract the integer value (or default to 1 if no integer is attached)

        switch ($unit) {
            case 'm':
                $result['m'][] = ($value !== 0) ? $timeString : '1m';
                break;
            case 'd':
                $result['d'][] = ($value !== 0) ? $timeString : '1d';
                break;
            case 'h':
                $result['h'][] = ($value !== 0) ? $timeString : '1h';
                break;
            case 'i':
                $result['i'][] = ($value !== 0) ? $timeString : '1i';
                break;
            case 's':
                $result['s'][] = ($value !== 0) ? $timeString : '1s';
                break;
            default:
                break;
        }
    }

    foreach ($result as &$array) {
        usort($array, function ($a, $b) {
            $aValue = intval(preg_replace('/[^\d]/', '', $a)); // Extract the integer value
            $bValue = intval(preg_replace('/[^\d]/', '', $b));

            return $bValue - $aValue; // Sort in descending order
        });
    }

    return $result;
}



