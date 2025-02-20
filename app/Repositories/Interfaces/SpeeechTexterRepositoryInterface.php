<?php

namespace SpeeechTexter\Repositories\Interfaces;

interface SpeeechTexterRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters);
}
