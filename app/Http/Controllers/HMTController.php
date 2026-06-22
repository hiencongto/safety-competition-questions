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
            // $dataPool = [
            //     [
            //         "id" => 1,
            //         "question" => "Ngày 02/02/2026 khách hàng ABR khai đơn NCM NA sản phẩm NISSAN P02H SAB về vấn đề gì ewefewfewfewfwefwefewfewfewfewfewfewfefwfềwfwefewfwefwefwefwefwef",
            //         "answer" => "1.Người lái xe trước tiên phải thắt dây an toàn khi vận hành xe nâng; 2. Không ai được phép đến gần trong phạm vi 1.5 m hoạt động của xe nâng 3. Khi lùi xe nâng, trước tiên người lái xe phải kiểm tra; tình hình xung quanh và xác nhận rằng nó an toàn mới được lùi xe; 4. Không được phép đứng hoặc chở người khác trên tất cả các bộ phận của xe nâng; khi lái xe chở hàng hóa không được vượt tải, khi chở hàng khồng được chở hàng quá cao;",
            //         "is_answered" => 0,
            //         "created_at" => "2026-06-22T03:01:58.000000Z",
            //         "updated_at" => "2026-06-22T09:00:28.000000Z"
            //     ]
            // ];

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
