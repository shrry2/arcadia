<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use \App\Setting;

class SettingController extends Controller
{
    public $labels = [
        'system_name' => 'システム名',
    ];

    public function edit(Request $request)
    {
        $settings = Setting::all();
        return view('setting.edit', ['settings' => $settings, 'labels' => $this->labels]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'system_name' => ['required', 'string', 'max:20'],
        ]);

        foreach ($request->input() as $key => $value) {
            if (array_key_exists($key, $this->labels)) {
                $setting = Setting::find($key);
                $setting->value = $value;
                $setting->save();
            }
        }

        return redirect(route('settings'))
            ->with('message', ['status'=>'success', 'body'=>"全般設定を更新しました"]);
    }
}
