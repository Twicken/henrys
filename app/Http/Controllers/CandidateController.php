<?php

namespace App\Http\Controllers;

use App\Services\ReferooService;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    protected $referooService;

    public function __construct(ReferooService $referooService)
    {
        $this->referooService = $referooService;
    }

    public function index(Request $request)
    {
        $limit = $request->input('limit');
        $offset = $request->input('offset');
        $candidates = $this->referooService->getCandidates($limit, $offset);
        return response()->json($candidates);
    }
}