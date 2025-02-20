<?php

namespace App\Services\Ussistant\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Ussistant\Repositories\Interfaces\UssistantRepositoryInterface;

class UssistantController extends Controller
{
    private UssistantRepositoryInterface $repository;
    
    public function __construct(UssistantRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function list() 
    {
        return $this->repository->list();
    }
}
