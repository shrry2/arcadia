@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <div class="container">
                <a href="{{ route('office.list') }}">&laquo; 部署リスト</a>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <h2>部署（{{ $office->name }}）の削除</h2>
            </div>
        </div>
        <div class="row">
            <div class="container">
                <div class="alert alert-danger">
                    <strong>部署の削除に関する注意事項</strong>
                    <ul class="mb-0">
                        <li>一度削除した部署情報はもとに戻せません</li>
                        <li>すでにそのグループに所属するスタッフがいた場合、その所属情報が削除されます</li>
                        <li>すでにその部署に所属する共有ボードが存在した場合、その所属情報が削除されます</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                            <form action="{{ route('office.destroy', $office->id) }}" method="post">
                                @method('delete')
                                @csrf
                                注意事項を確認したうえで削除を実行する場合は、こちらのボタンを押してください → <button type="submit" class="btn btn-danger">部署を削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
