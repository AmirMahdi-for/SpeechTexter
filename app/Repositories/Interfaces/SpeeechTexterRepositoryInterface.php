<?php

namespace App\Repositories\Interfaces;

interface SpeeechTexterRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters);
}