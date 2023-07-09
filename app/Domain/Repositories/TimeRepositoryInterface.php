<?php

namespace App\Domain\Repositories;


use App\Models\Time;

interface TimeRepositoryInterface
{
    public function save(Time $time);

    public function findWhere(array $criteria);
}
