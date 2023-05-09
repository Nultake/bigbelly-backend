<?php

namespace App\Http\Controllers;

use App\Helpers\JsonResponse\JsonResponse;
use App\Models\CommentReport;
use App\Models\PostReport;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function comment(Request $request)
    {
        CommentReport::create([
            'reason' => $request->input('reason'),
            'comment_id' => $request->input('comment_id'),
            'account_id' => $request->input('account_id')
        ]);

        return JsonResponse::success();
    }

    public function post(Request $request)
    {
        PostReport::create([
            'reason' => $request->input('reason'),
            'post_id' => $request->input('post_id'),
            'account_id' => $request->input('account_id')
        ]);

        return JsonResponse::success();
    }
}
