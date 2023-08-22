<?php

namespace App\Http\Api\Controllers;

use App\Http\Api\Requests\IntervalRequest;
use App\Http\Api\Resources\RInterval;
use App\Services\IntervalService;
use Illuminate\Http\JsonResponse;

class IntervalController extends Controller
{
    public function show(IntervalRequest $request, IntervalService $service): JsonResponse
    {
        $data = collect($request->input('data.seances', []));
        $freeIntervals = $service->freeIntervals($data);
        return response()->json(new RInterval($freeIntervals));
    }
}
