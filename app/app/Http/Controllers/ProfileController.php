<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use \App\User;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->fill(['name' => $request->input('name')]);
        $user->fill(['email' => $request->input('email')]);
        if (!empty($request->input('password'))) {
            $user->password = Hash::make($request->input('password'));
            $user->setRememberToken(Str::random(60));
        }
        $user->save();

        return redirect(route('profile'))
            ->with('message', ['status'=>'success', 'body'=>"自身のスタッフ情報を更新しました"]);
    }
}
