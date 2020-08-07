@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row mb-4">
            <a href="{{ route('user.list') }}">&laquo; スタッフリスト</a>
        </div>
        <div class="row">
            <h2>スタッフ（{{ $user->name }}）の削除</h2>
        </div>
        <div class="row">
            <div class="container">
                <div class="alert alert-danger">
                    <strong>スタッフの削除に関する注意事項</strong>
                    <ul class="mb-0">
                        <li>一度削除したスタッフはもとに戻せません</li>
                        <li>該当のスタッフが過去にこのシステム上に作成したあらゆるデータも共に削除されます</li>
                        <li>退職などでスタッフのアクセス権限だけを削除したい場合は、スタッフデータは残したまま権限グループだけ削除することを検討してください</li>
                    </ul>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="text-right">
                            <form action="{{ route('user.destroy', $user->id) }}" method="post">
                                @method('delete')
                                @csrf
                                注意事項を確認したうえで削除を実行する場合は、こちらのボタンを押してください → <button type="submit" class="btn btn-danger">スタッフを削除</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
