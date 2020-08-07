<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Attendance;
use App\User;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return 'Hello';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * イントラネット出退勤記録
     */

    protected function backWithError($request, $message) {
        $errors = [['identity' => $message]];
        return Redirect::back()
            ->withInput($request->input())
            ->withErrors($errors);
    }

    // 出勤記録
    public function begin()
    {
        return view('attendance.begin');
    }

    public function storeBegin(Request $request)
    {
        $input = $request->all();

        if (filter_var($input['identity'], FILTER_VALIDATE_EMAIL)) {
            $credentials = ['email' => $input['identity'], 'password' => $input['password']];
        } else {
            $credentials = ['username' => $input['identity'], 'password' => $input['password']];
        }

        if (Auth::attempt($credentials)) {
            // 認証に成功した
            // スタッフ情報だけいただいて、すぐログアウト
            $user = Auth::user();
            Auth::logout();

            // 所属しているかチェック
            if (!$request->intranet->office->members->contains($user->id)) {
                return $this->backWithError($request, 'あなたは現在の部署に所属登録されていませんので、出勤登録はできません');
            }

            // すでに出勤登録済みでないかチェック
            if ($request->intranet->office->workingMembers->contains($user->id)) {
                return $this->backWithError($request, 'すでに出勤登録済みです。再度登録するには一度退勤してください。');
            }

            // 出勤レコードを記録
            Attendance::create([
                'user_id' => $user->id,
                'office_id' => $request->intranet->office->id,
            ]);

            return redirect('/')
                ->with('message', ['status' => 'success', 'body' => "お疲れさまです。{$user->name}さんの出勤時刻を登録しました"]);
        }

        return $this->backWithError($request, '認証に失敗しました。再度お試しください');
    }

    public function apiListWorkingMembers(Request $request)
    {
        $members = $request->intranet->office->workingMembers;
        $response = [];
        foreach($members as $member) {
            $response[] = [
                'id' => $member->id,
                'name' => $member->name,
                'begin_at' => $member->attendance->begin_at,
            ];
        }
        return $response;
    }

    public function apiFinishWorking(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'password' => 'required|string',
        ]);

        $user = User::find($request->input('user_id'));

        // パスワード認証
        if (!Auth::attempt(['id' => $user->id, 'password' => $request->input('password')])) {
            return response(['success' => false, 'message' => '認証に失敗しました。パスワードを確認してください'], 400);
        }

        Auth::logout();

        // 出勤登録されているかチェック
        if (!$request->intranet->office->workingMembers->contains($user->id)) {
            return response(['success' => false, 'message' => '出勤登録されていません'], 400);
        }

        // 退勤時刻を記録する
        $attendance = $user->attendances
            ->where('finish_at', null)
            ->where('office_id', $request->intranet->office->id)
            ->first();

        $attendance->finish_at = Carbon::now();
        $attendance->save();

        return response()->json(['success' => true, 'message' => "{$user->name}さんの退勤時刻を記録しました"]);
    }
}
