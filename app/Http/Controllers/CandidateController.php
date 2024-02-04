<?php

namespace App\Http\Controllers;

use App\Services\ReferooService;
use Inertia\Inertia;

class CandidateController extends Controller
{
    protected $referooService;

    public function __construct(ReferooService $referooService)
    {
        $this->referooService = $referooService;
    }

    public function index()
    {
        $candidates = $this->referooService->getCandidates();

        return Inertia::render('Candidates/Index', [
            'candidates' => $candidates['data'] ?? [],
        ]);
    }
}
