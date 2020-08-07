<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

use App\Task;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    // タスクリスト
    public function index()
    {
        $tasks = Task::paginate(15);
        return view('task.list', ['tasks' => $tasks]);
    }

    // タスク新規作成（CRUD）
    public function create()
    {
        return view('task.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $colors = ['default', 'blue', 'green', 'red', 'yellow', 'black'];
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:100000'],
            'color' => ['required', Rule::in($colors)],
            'ip' => ['required', 'ip'],
            'mask' => ['nullable', 'integer', 'between:1,128'],
        ]);
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

    public function searchMember(Request $request)
    {
        $query = User::query();
        if ($request->filled('key')) {
            $request->validate(['key' => 'string|max:255']);
            $key = $request->input('key');
            $query->where('name', 'like', "%$key%")
                ->orWhere('username', $key);
        } else {
            // キー指定なし
            // ログイン済み -> 自分が所属する部署のメンバーをフィルター
            if (Auth::check()) {
                $myOfficeIds = $request->user()->offices->pluck('id')->toArray();
                $query->whereHas('offices', function (Builder $subQuery) use ($myOfficeIds) {
                    $subQuery->whereIn('id', $myOfficeIds);
                });
            } else if ($request->fromIntranet) {
                // イントラネットからのアクセス -> 自部署のメンバーをフィルター
                $query->whereHas('offices', function (Builder $subQuery) use ($request) {
                    $subQuery->where('id', $request->intranet->office->id);
                });
            }
        }

        $users = $query->get();

        $response = [];

        foreach($users as $user) {
            $response[] = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'offices_str' => implode(', ', $user->offices->pluck('name')->toArray()),
            ];
        }

        return $response;
    }
}
