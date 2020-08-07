<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Office;

class OfficeController extends Controller
{
    public function index() {
        $offices = Office::All();
        return view('office.list', ['offices' => $offices]);
    }

    public function add() {
        return view('office.add');
    }

    public function edit($id) {
        $office = Office::findOrFail($id);
        return view('office.edit', ['office' => $office]);
    }

    public function update(Request $request, $id) {
        $office = Office::findOrFail($id);

        $request->validate([
            'name' => 'required|max:60',
        ]);

        $office->name = $request->input('name');
        $office->save();

        return redirect(route('office.list', $office->id))
            ->with('message', ['status'=>'success', 'body'=>"部署情報を更新しました"]);
    }

    public function create(Request $request) {
        $request->validate([
            'name' => 'required|max:60',
        ]);

        $office = Office::create([
            'name' => $request->input('name'),
        ]);

        return redirect(route('office.list', $office->id))
            ->with('message', ['status'=>'success', 'body'=>"新しい部署「{$office->name}」を作成しました"]);
    }

    public function delete($id)
    {
        $office = Office::findOrFail($id);
        return view('office.delete', ['office' => $office]);
    }

    public function destroy($id)
    {
        $office = Office::findOrFail($id);
        $office->delete();

        return redirect(route('office.list'))
            ->with('message', ['status'=>'success', 'body'=>"部署を削除しました"]);
    }
}
