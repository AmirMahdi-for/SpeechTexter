<?php

namespace App\Services\Ussistant\Repositories\Interfaces;

interface UssistantRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters);
}