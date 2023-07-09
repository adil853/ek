<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Repositories\TimeRepositoryInterface;
use App\Models\Time;

class TimeRepository implements TimeRepositoryInterface
{
    public function save(Time $time)
    {
        $time->save();
    }

    public function findWhere(array $criteria)
    {
        return Time::where($criteria)->get();
    }

}
