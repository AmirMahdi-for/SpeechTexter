<?php

namespace App\Services\SpeeechTexter\Controllers;

use App\Http\Controllers\Controller;
use App\SpeeechTexter\Repositories\Interfaces\SpeeechTexterRepositoryInterface;

class SpeeechTexterController extends Controller
{
    private SpeeechTexterRepositoryInterface $repository;
    
    public function __construct(SpeeechTexterRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function list() 
    {
        return $this->repository->list();
    }
}
