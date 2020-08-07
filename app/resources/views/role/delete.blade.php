@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="container">
                <a href="{{ route('role.list') }}">&laquo; 権限グループリスト</a>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <h2>権限グループ（{{ $role->name }}）の削除</h2>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="alert alert-danger">
                    <strong>権限グループの削除に関する注意事項</strong>
                    <ul class="mb-0">
                        <li>一度削除した権限グループはもとに戻せません</li>
                        <li>すでにそのグループに所属しているスタッフがいた場合、その所属情報が削除されます</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                            <form action="{{ route('role.destroy', $role->id) }}" method="post">
                                @method('delete')
                                @csrf
                                注意事項を確認したうえで削除を実行する場合は、こちらのボタンを押してください → <button type="submit" class="btn btn-danger">権限グループを削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
