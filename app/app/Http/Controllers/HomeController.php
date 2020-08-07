<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // 個人認証済みならスタッフダッシュボード
        if ($request->user()) {
            return view('dashboard.user');
        }

        // イントラネットなら部署ダッシュボード
        if ($request->fromIntranet) {
            $boards = $request->intranet->office->boards;
            return view('dashboard.office', ['boards' => $boards]);
        }

        // その他は不明
        return abort(500, '予期しないエラーが発生しました。 エラーコード: 11023');
    }
}
