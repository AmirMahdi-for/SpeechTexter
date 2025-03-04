<?php

namespace SpeechTexter\Repositories\Interfaces;

interface SpeechTexterRepositoryInterface
{
    public function speechToText(int $userId, int $fileId, array $parameters);
}
