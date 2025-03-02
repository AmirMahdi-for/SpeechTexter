<?php

namespace SpeechTexter\Controllers;

use App\Http\Controllers\Controller;
use SpeechTexter\Repositories\Interfaces\SpeechTexterRepositoryInterface;

class SpeechTexterController extends Controller
{
    private SpeechTexterRepositoryInterface $repository;
    
    public function __construct(SpeechTexterRepositoryInterface $repository) 
    {
        $this->repository = $repository;
    }

    public function list() 
    {
        return 'list';
    }
}
