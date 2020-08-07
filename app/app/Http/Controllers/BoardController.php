<?php

namespace App\Http\Controllers;

use App\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use App\Board;
use App\Office;
use App\User;

class BoardController extends Controller
{
    // ボードリスト
    public function index()
    {
        $boards = Board::paginate(15);
        return view('board.index', ['boards' => $boards]);
    }

    // ボード新規作成
    public function create()
    {
        $offices = Office::All();
        return view('board.create', ['offices' => $offices]);
    }

    public function store(Request $request)
    {
        $officeIds = Office::All()->pluck('id')->toArray();

        $request->validate([
            'name' => ['required', 'max:60'],
            'office' => ['required', 'array', 'min:1'],
            'office.*' => [Rule::in($officeIds)],
        ]);

        // ボードを作成
        $board = Board::create([
            'name' => $request->input('name'),
        ]);

        // 部署を割り当て
        $selectedOfficeIds = array_map('intval', $request->input('office.*'));
        $board->offices()->attach($selectedOfficeIds);

        return redirect(route('board.index'))
            ->with('message', ['status'=>'success', 'body'=>"新しいボード「{$board->name}」を作成しました"]);
    }

    public function show($id)
    {
        $board = Board::findOrFail($id);

        return view('board.viewer', ['board' => $board]);
    }

    // タスクリスト
    public function apiListTask($id)
    {
        $board = Board::findOrFail($id);

        $tasks = $board->tasks;

        return $tasks;
    }

    // タスク作成
    public function apiStoreTask(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:100000'],
        ]);

        if($request->user()) {
            $userId = $request->user()->id;
        } else if ($request->operator) {
            $userId = $request->operator->id;
        } else {
            return ['message' => 'エラー', 'errors' => ['operator' => '担当者を選択してください']];
        }

        $task = Task::create([
            'board_id' => $board->id,
            'user_id' => $userId,
            'title' => $request->input('title'),
            'note' => $request->input('note'),
            'status' => 'backlog',
            'color' => 'default',
        ]);

        return ['success' => true, 'task' => $task];
    }

    public function edit(Request $request, $id)
    {
        $board = Board::findOrFail($id);
        $offices = Office::All();
        return view('board.edit', ['offices' => $offices, 'board' => $board]);
    }

    public function update(Request $request, $id)
    {
        $board = Board::findOrFail($id);

        $officeIds = Office::All()->pluck('id')->toArray();

        $request->validate([
            'name' => ['required', 'max:60'],
            'office' => ['required', 'array', 'min:1'],
            'office.*' => [Rule::in($officeIds)],
        ]);

        $board->name = $request->input('name');
        $board->save();

        $selectedOfficeIds = array_map('intval', $request->input('office.*'));
        $board->offices()->sync($selectedOfficeIds);

        return redirect(route('board.index'))
            ->with('message', ['status'=>'success', 'body'=>"ボード情報を更新しました"]);
    }

    public function destroy($id)
    {
        $board = Board::findOrFail($id);
        $board->delete();

        return redirect(route('board.index'))
            ->with('message', ['status'=>'success', 'body'=>"ボードを削除しました"]);
    }
}
