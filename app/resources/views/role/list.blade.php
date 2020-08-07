@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-3">
            <div class="col-sm">
                <h2>権限グループリスト</h2>
            </div>
            <div class="col-sm text-right">
                <a href="{{ route('role.add') }}" class="btn btn-success">新規登録</a>
            </div>
        </div>
        <div class="row">
            <div class="table-responsive">
                <table class="table table-hover" style="min-width: 900px;">
                    <thead>
                    <tr>
                        <th scope="col">権限グループ名</th>
                        <th scope="col" style="max-width: 600px">許可されている操作</th>
                        <th scope="col">登録日時</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <th>{{ $role->name }}</th>
                            <td style="max-width: 600px">
                                <ul class="list-inline">
                                    @foreach($role->permissions as $permission)
                                        <li class="list-inline-item">{{ $permissionLabels[$permission->name] }}</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>{{ $role->created_at }}</td>
                            <td>
                                <a href="{{ route('role.edit', $role->id) }}" class="btn btn-primary">
                                    編集
                                </a>
                                <a href="{{ route('role.delete', $role->id) }}" class="btn btn-danger">
                                    削除
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
