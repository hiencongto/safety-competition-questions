<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnserDatabaseService;

class HMTController extends Controller
{
    // python public\data\read-excel.py
    // php artisan questions:import

    private $anserDatabaseService;

    public function __construct(AnserDatabaseService $anserDatabaseService)
    {
        $this->anserDatabaseService = $anserDatabaseService;
    }

    public function home()
    {
        return view("background");
    }

    public function quesAndAns()
    {
        $dataPool = $this->anserDatabaseService->getUnanswered();

        return view("quesandans", compact('dataPool'));
    }

    public function markAnswered(Request $request)
    {
        $id = $request->input('id');

        if (!$id) {
            return response()->json(['error' => 'Missing id'], 400);
        }

        $item = $this->anserDatabaseService->findById((int) $id);

        if (!$item) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $updated = $this->anserDatabaseService->markAsAnswered((int) $id);

        if (!$updated) {
            return response()->json(['error' => 'Unable to update'], 500);
        }

        return response()->json(['success' => true]);
    }

    public function resetAnswered()
    {
        $updated = $this->anserDatabaseService->resetAll();

        if (!$updated) {
            return response()->json(['error' => 'Unable to reset'], 500);
        }

        return response()->json(['success' => true]);
    }
}
