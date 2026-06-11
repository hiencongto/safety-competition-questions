<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;
use App\Services\AnserDatabaseService;

class HMTController extends Controller
{
    //  python public\data\read-excel.py
    // php artisan questions:import

    private $anserDatabaseService;

    public function __construct(AnserDatabaseService $anserDatabaseService)
    {
        $this->anserDatabaseService = $anserDatabaseService;
    }

    public function home()
    {
        $dataPool = $this->anserDatabaseService->getUnanswered();

        return view("background", compact('dataPool'));
    }

    public function quesAndAns()
    {
        return view("quesandans");
    }
}
