<?php

namespace App\Services;

use Carbon\Carbon;
use function PHPUnit\Framework\returnArgument;

const SecondsInMonth = 2592000;
const SecondsInDay = 86400;
const SecondsInHour = 3600;
const SecondsInMin = 60;
const SecondsInSecond = 1;

class TimeService
{

    public function breakTime(array $data): array
    {
        $timeExpressions = $data["time_expressions"];
        $sortedExpressionTimeStrings = sortTimeStrings($timeExpressions);
        $response = [];
        $startTime = strtotime($data["start_time"]);
        $endTime = strtotime($data["end_time"]);
        $totalSeconds = $endTime - $startTime;

        $alreadyValidExpressionExtracted["m"] = false;
        $alreadyValidExpressionExtracted["d"] = false;
        $alreadyValidExpressionExtracted["h"] = false;
        $alreadyValidExpressionExtracted["i"] = false;
        $alreadyValidExpressionExtracted["s"] = false;

        $count = 0;
        while ($count < count($sortedExpressionTimeStrings)) {
            $timeExpression = $sortedExpressionTimeStrings[$count];
            preg_match('/^(\d+)([a-zA-Z]+)$/', $timeExpression, $match);
            $quantity = $match[1];
            $unit = $match[2];
            echo  $alreadyValidExpressionExtracted[$unit];
            if ($alreadyValidExpressionExtracted[$unit]) {
                return [
                    'error' => true,
                    'message' => 'There can be only one valid expression of each type at a time. i.e. if expressions array contains 2m, 1m and a valid 2m is already found then error will be thrown as 1m will become invalid',
                    'body' => $unit
                ];
            }
            $secondsToDividedBy = 0;
            switch ($unit) {
                case "m" :
                    $secondsToDividedBy = SecondsInMonth;
                    break;
                case "d" :
                    $secondsToDividedBy = SecondsInDay;
                    break;
                case "h" :
                    $secondsToDividedBy = SecondsInHour;
                    break;
                case "i" :
                    $secondsToDividedBy = SecondsInMin;
                    break;
                case "s" :
                    $secondsToDividedBy = SecondsInSecond;
                    break;
            }
            if ($count == count($sortedExpressionTimeStrings) - 1) {
                if ($secondsToDividedBy > 0) {
                    $lastValue = $totalSeconds / ($secondsToDividedBy * $quantity);
                    $response[$quantity . $unit] = $lastValue;
                }
            } else {
                if (($totalSeconds / ($secondsToDividedBy * $quantity)) >= 1) {
                    $totalTime = $totalSeconds / ($secondsToDividedBy * $quantity);
                    $expressionQuantity = intval($totalTime);
                    $totalSeconds = ($totalTime - $expressionQuantity) * $secondsToDividedBy;
                    $response[$quantity . $unit] = $expressionQuantity;
                    $alreadyValidExpressionExtracted[$unit] = true;
                } else {
                    $response[$quantity . $unit] = 0;
                }
            }
            $count++;
        }
        return [
            'error' => false,
            'message' => 'success',
            'body' => $response
        ];
    }
}


function sortTimeStrings(array $timeStrings): array
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
    return array_merge($result["m"], $result["d"], $result["h"], $result["i"], $result["s"]);
}

