<?php

namespace App\Domain\Repositories;


use App\Models\Time;

interface TimeRepositoryInterface
{
    public function save(Time $time);
}
