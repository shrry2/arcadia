<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use \Spatie\Permission\Models\Role;
use \Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public const PermissionLabels = [
        'outside access' => '社外からのアクセス',
        'edit master settings' => 'システム設定の変更',
        'register users' => '新規スタッフ登録',
        'edit users' => 'スタッフ情報の変更',
        'edit permissions' => '権限グループの変更',
        'read cards' => 'カードの読み取り',
        'edit cards' => 'カードの作成と変更'
    ];

    public function index(Request $request)
    {
        $roles = Role::All();

        return view('role.list', [
            'roles' => $roles,
            'permissionLabels' => $this::PermissionLabels,
        ]);
    }

    public function add(Request $request)
    {
        $permissions = Permission::All();
        return view('role.add', ['permissions' => $permissions]);
    }

    public function create(Request $request)
    {
        $permissionIds = Permission::All()->pluck('id')->toArray();

        $request->validate([
            'name' => 'required|max:60',
            'permission.*' => ['nullable', Rule::in($permissionIds)],
        ]);

        $role = Role::create([
            'name' => $request->input('name'),
        ]);

        $selectedPermissionIds = array_map('intval', $request->input('permission.*'));

        $role->syncPermissions($selectedPermissionIds);

        return redirect(route('role.list'))
            ->with('message', ['status'=>'success', 'body'=>"新しい権限グループ「{$role->name}」を作成しました"]);
    }

    public function edit(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::All();

        return view('role.edit', ['role' => $role, 'permissions' => $permissions]);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $permissionIds = Permission::All()->pluck('id')->toArray();

        $request->validate([
            'name' => 'required|max:60',
            'permission.*' => ['nullable', Rule::in($permissionIds)],
        ]);

        $role->name = $request->input('name');
        $role->save();

        $selectedPermissionIds = array_map('intval', $request->input('permission.*'));

        $role->syncPermissions($selectedPermissionIds);

        return redirect(route('role.edit', $role->id))
            ->with('message', ['status'=>'success', 'body'=>"権限グループ「{$role->name}」を更新しました"]);
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);
        return view('role.delete', ['role' => $role]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect(route('role.list'))
            ->with('message', ['status'=>'success', 'body'=>"権限グループを削除しました"]);
    }
}
