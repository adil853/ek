<?php

namespace App\Services;

class TimeService
{


    public function breakTime(array $data): string
    {
        $startTime = $data["start_time"];
        $endTime = $data["end_time"];
        $timeExpressions = $data["time_expressions"];
        $sortedExpressionTimeStrings = sortTimeStrings($timeExpressions);

        $time1 = strtotime($startTime);
        $time2 = strtotime($endTime);

        $totalSeconds = $time2 - $time1;

        $secondsInMonth = 2592000;
        $secondsInDay = 86400;
        $secondsInHour = 3600;
        $secondsInMin = 60;
        $secondsInSecond = 1;

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
            if ($alreadyValidExpressionExtracted[$unit]) {
                $count++;
                continue;
            }
            if ($count == count($sortedExpressionTimeStrings) - 1) {

                $secondsToDividedBy = 0;
                switch ($unit) {
                    case "m" :
                        $secondsToDividedBy = $secondsInMonth;
                        break;
                    case "d" :
                        $secondsToDividedBy = $secondsInDay;

                        break;
                    case "h" :
                        $secondsToDividedBy = $secondsInHour;
                        break;
                    case "i" :
                        $secondsToDividedBy = $secondsInMin;
                        break;
                    case "s" :
                        $secondsToDividedBy = $secondsInSecond;
                        break;
                }
                if ($secondsToDividedBy > 0) {
                    $lastValue = $totalSeconds / ($secondsToDividedBy * $quantity);
                    echo $lastValue . " unit of " . $quantity . " " . $unit . "___this is last one___";
                }

            } else {
                switch ($unit) {
                    case "m" :
                        if (($totalSeconds / ($secondsInMonth * $quantity)) > 1) {
                            $totalTimeInMonth = $totalSeconds / ($secondsInMonth * $quantity);
                            $expressionQuantity = intval($totalTimeInMonth);
                            $totalSeconds = ($totalTimeInMonth - $expressionQuantity) * $secondsInMonth;
                            echo $expressionQuantity . "unit of " . $quantity . "m___";
                            $alreadyValidExpressionExtracted["m"] = true;
                        } else {
                            echo "0 unit of " . $quantity . "m___";
                        }
                        break;
                    case "d" :
                        if (($totalSeconds / ($secondsInDay * $quantity)) > 1) {
                            $totalTimeInDay = $totalSeconds / ($secondsInDay * $quantity);
                            $expressionQuantity = intval($totalTimeInDay);
                            $totalSeconds = ($totalTimeInDay - $expressionQuantity) * $secondsInDay;
                            echo $expressionQuantity . "unit of " . $quantity . "d___";
                            $alreadyValidExpressionExtracted["d"] = true;
                        } else {
                            echo "0 unit of " . $quantity . "d___";
                        }
                        break;
                    case "h" :
                        if (($totalSeconds / ($secondsInHour * $quantity)) > 1) {
                            $totalTimeInHour = $totalSeconds / ($secondsInHour * $quantity);
                            $expressionQuantity = intval($totalTimeInHour);
                            $totalSeconds = ($totalTimeInHour - $expressionQuantity) * $secondsInHour;
                            echo $expressionQuantity . "unit of " . $quantity . "h___";
                            $alreadyValidExpressionExtracted["h"] = true;
                        } else {
                            echo "0 unit of " . $quantity . "h___";
                        }
                        break;
                    case "i" :
                        if (($totalSeconds / ($secondsInMin * $quantity)) > 1) {
                            $totalTimeInMin = $totalSeconds / ($secondsInMin * $quantity);
                            $expressionQuantity = intval($totalTimeInMin);
                            $totalSeconds = ($totalTimeInMin - $expressionQuantity) * $secondsInMin;
                            echo $expressionQuantity . "unit of " . $quantity . "i___";
                            $alreadyValidExpressionExtracted["i"] = true;
                        } else {
                            echo "0 unit of " . $quantity . "i___";
                        }
                        break;
                    case "s" :
                        if (($totalSeconds / ($secondsInSecond * $quantity)) > 1) {
                            $totalTimeInSecond = $totalSeconds / ($secondsInSecond * $quantity);
                            $expressionQuantity = intval($totalTimeInSecond);
                            $totalSeconds = ($totalTimeInSecond - $expressionQuantity) * $secondsInSecond;
                            echo $expressionQuantity . "unit of " . $quantity . "i___";
                            $alreadyValidExpressionExtracted["i"] = true;
                        } else {
                            echo "0 unit of " . $quantity . "i___";
                        }
                        break;
                }
            }


            $count++;
        }


        return "breakTime from service is called";
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



