<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use \Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Builder;

use \App\User;
use \App\Office;

class UserController extends Controller
{
    public const USER_ROLES = [
        'maintainer' => 'メンテナンス担当者',
        'admin' => '管理者',
        'employee' => '社員',
        'staff' => 'スタッフ',
        'retiree' => '退職者',
    ];

    public const PERMISSIONS = [
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
        $offices = Office::All();
        $roles = Role::All();

        $officeIds = $offices->pluck('id')->toArray();
        $roleIds = $roles->pluck('id')->toArray();

        $query = User::query();
        $isFiltered = false;

        if ($request->hasAny(['name', 'email', 'office', 'role'])) {
            $request->validate([
                'name' => 'nullable|max:255',
                'email' => 'nullable|max:255',
                'office.*' => ['nullable', Rule::in($officeIds)],
                'role.*' => ['nullable', Rule::in($roleIds)],
            ]);

            $isFiltered = true;

            if ($request->input('name')) {
                $name = $request->input('name');
                $query->where('name', 'like', "%$name%");
            }

            if($request->input('email')) {
                $query->where('email', $request->input('email'));
            }

            if ($request->input('office.*')) {
                $officeIds = array_map('intval', $request->input('office.*', []));
                $query->whereHas('offices', function (Builder $subQuery) use ($officeIds) {
                    $subQuery->whereIn('id', $officeIds);
                });
            }

            if ($request->input('role.*')) {
                $roleIds = array_map('intval', $request->input('role.*', []));
                $query->role($roleIds);
            }
        }

        $users = $query->paginate(15);

        return view('user.list', [
            'users' => $users,
            'isFiltered' => $isFiltered,
            'offices' => $offices,
            'roles' => $roles,
            'selectedOffices' => $officeIds,
            'selectedRoles' => $roleIds,
        ]);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        return view('user.delete', ['user' => $user]);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect(route('user.list'))
            ->with('message', ['status'=>'success', 'body'=>"スタッフを削除しました"]);
    }

    public function edit(Request $request, $id)
    {
        $roles = Role::All();
        $offices = Office::All();

        return view('user.edit', [
            'user' => User::findOrFail($id),
            'offices' => $offices,
            'roles' => $roles,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $prevRoleIds = $user->roles->pluck('id')->toArray();

        $roleIds = Role::All()->pluck('id')->toArray();
        $officeIds = Office::All()->pluck('id')->toArray();

        $isRenewingUsername = $request->input('username') !== $user->username;
        $usernameValidations = ['required', 'string', 'max:255'];
        if ($isRenewingUsername) {
            $usernameValidations[] = 'unique:users';
        }

        $isRenewingEmail = $request->input('email') !== $user->email;
        $emailValidations = ['nullable', 'string', 'email', 'max:255'];
        if ($isRenewingEmail) {
            $emailValidations[] = 'unique:users';
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => $usernameValidations,
            'email' => $emailValidations,
            'office.*' => ['nullable', Rule::in($officeIds)],
            'role.*' => ['nullable', Rule::in($roleIds)],
        ]);

        /**
         * 基本情報の更新
         */
        $user->fill(['name' => $request->input('name')]);
        if ($isRenewingUsername) {
            $user->fill(['username' => $request->input('username')]);
        }
        $user->fill(['email' => $request->input('email')]);
        $user->save();

        /**
         * 所属の更新
         */
        $newOfficeIds = array_map('intval', $request->input('office.*', []));
        $user->offices()->sync($newOfficeIds);

        /**
         * 権限グループの更新
         */
        if ($request->user()->hasPermissionTo('edit permissions')) {
            $newRoles = array_map('intval', $request->input('role.*', []));
            $user->syncRoles($newRoles);

            // 最低でも一人の権限グループ管理者がいるかどうかチェック
            $roleEditableUsers = User::permission('edit permissions')->get();
            if (count($roleEditableUsers) <= 0) {
                // 権限グループ管理者が居なくなってしまったのでロールバック
                $user->syncRoles($prevRoleIds);
                return redirect(route('user.edit', $user->id))
                    ->with('message', ['status'=>'danger', 'body'=>"最低でも一人の権限グループ管理者が必要なため、変更できません"]);
            }
        }

        return redirect(route('user.edit', $user->id))
            ->with('message', ['status'=>'success', 'body'=>"{$user->name}さんのスタッフ情報を更新しました"]);
    }

    public function editPassword($id)
    {
        return view('user.editPassword', ['user' => User::findOrFail($id)]);
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole(['maintainer', 'admin']) && !$request->user()->hasRole('maintainer')) {
            return redirect(route('user.edit', $user->id))
                ->with('message', ['status'=>'danger', 'body'=>"エラー: 管理者およびメンテナンススタッフのパスワードは、自身またはメンテナンススタッフのみ変更可能です"]);
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->password = Hash::make($request->input('password'));
        $user->setRememberToken(Str::random(60));

        $user->save();

        return redirect(route('user.edit', $user->id))
            ->with('message', ['status'=>'success', 'body'=>"{$user->name}さんのパスワードを更新しました"]);
    }
}
